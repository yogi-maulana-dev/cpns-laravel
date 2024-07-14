<?php

namespace App\Livewire\Tables;

use App\Models\QuestionGroupType;
use App\Models\QuestionType;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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

final class QuestionTypeTable extends PowerGridComponent
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
            QuestionType::whereIn('id', $ids)->delete();
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
     * @return Builder<\App\Models\QuestionType>
     */
    public function datasource(): Builder
    {
        return QuestionType::query()
            ->leftJoin('question_group_types', 'question_types.question_group_type_id', 'question_group_types.id')
            ->select('question_types.*', 'question_group_types.name as question_group_type_name');
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
            ->addColumn('name')
            ->addColumn('question_group_type', fn (QuestionType $model) => BootstrapElementsService::badge(
                color: 'primary',
                type: 'a',
                child: e($model->question_group_type_name),
                attributes: [
                    'href' => route('question-group-types.show', $model->question_group_type_id),
                ]
            ))
            ->addColumn('created_at')
            ->addColumn(
                'created_at_formatted',
                fn (QuestionType $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s')
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

            Column::make('Nama Tipe Soal', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Tipe Kelompok Soal', 'question_group_type', 'question_group_types.name')
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
            Filter::multiSelect('question_group_type', 'question_group_types.id')
                ->dataSource(QuestionGroupType::orderBy('name')->get())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::datepicker('created_at', 'question_types.created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'question_types.created_at')
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
     * PowerGrid QuestionType Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(QuestionType $questionType): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('badge text-warning-emphasis bg-warning-subtle border border-warning-subtle')
                ->route('question-types.edit', ['question_type' => $questionType->id])->target(''),
            Button::make('detail', 'Detail')
                ->class('badge text-success-emphasis bg-success-subtle border border-success-subtle')
                ->route('question-types.show', ['question_type' => $questionType->id])->target(''),
        ];
    }
}
