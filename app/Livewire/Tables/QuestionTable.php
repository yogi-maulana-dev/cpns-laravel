<?php

namespace App\Livewire\Tables;

use App\Exceptions\PowergridException;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionType;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use App\Services\StorageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class QuestionTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected function getListeners()
    {
        return PowerGridTableService::getListeners(parent::getListeners());
    }

    public function header(): array
    {
        return PowerGridTableService::getHeader();
    }

    public function bulkCheckedDelete()
    {
        PowerGridTableService::bulkCheckedDelete($this, function (array $ids) {
            try {
                DB::beginTransaction();

                $questions = Question::query()
                    ->select('question_image', 'discussion_image')
                    ->whereIn('id', $ids)
                    ->get()
                    ->toArray();

                $answers = Answer::query()
                    ->select('answer_image')
                    ->whereNotNull('answer_image')
                    ->whereIn('question_id', $ids)
                    ->get()
                    ->toArray();

                Question::whereIn('id', $ids)->delete();
                Answer::whereIn('question_id', $ids)->delete();

                StorageService::public()->getStorage()->delete(
                    array_merge(
                        Arr::where(Arr::flatten($questions), fn ($item) => $item),
                        Arr::flatten($answers)
                    )
                );

                DB::commit();
            } catch (\Exception $ex) {
                throw new PowergridException('Ada masalah pada server, silahkan coba lagi nanti!');
                DB::rollBack();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput()->showToggleColumns(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Question>
     */
    public function datasource(): Builder
    {
        return Question::query()
            ->leftJoin('question_types', 'questions.question_type_id', 'question_types.id')
            ->select('questions.*', 'question_types.name as question_type_name');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('question_text', fn (Question $model) => nl2br(str($model->question_text)->words(7)))
            ->addColumn('question_type', fn (Question $model) => BootstrapElementsService::badge(
                color: 'primary',
                type: 'a',
                child: e($model->question_type_name),
                attributes: [
                    'href' => route('question-types.show', $model->question_type_id),
                ]
            ))
            ->addColumn('created_at')
            ->addColumn(
                'created_at_formatted',
                fn (Question $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s')
            );
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Isi Soal', 'question_text')
                ->searchable()
                ->sortable(),

            Column::make('Tipe Soal', 'question_type', 'question_types.name')
                ->sortable(),

            Column::make('Dibuat pada', 'created_at_formatted', 'created_at')
                ->sortable(),
            Column::action('Action'),
        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::multiSelect('question_type', 'question_types.id')
                ->dataSource(QuestionType::orderBy('name')->get())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::datepicker('created_at', 'questions.created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'questions.created_at')
            ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Question Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(Question $question): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('badge text-warning-emphasis bg-warning-subtle border border-warning-subtle')
                ->route('questions.edit', ['question' => $question->id])->target(''),
            Button::make('detail', 'Detail')
                ->class('badge text-success-emphasis bg-success-subtle border border-success-subtle')
                ->route('questions.show', ['question' => $question->id])->target(''),
        ];
    }
}
