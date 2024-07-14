<?php

namespace App\Livewire\Tables;

use App\Models\ParticipantExamResult;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use Illuminate\Database\Eloquent\Builder;
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

final class ParticipantExamResultTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public $examSessionId;

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
            ParticipantExamResult::where('exam_session_id', $this->examSessionId)->whereIn('id', $ids)->delete();
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
     * @return Builder<\App\Models\ParticipantExamResult>
     */
    public function datasource(): Builder
    {
        return ParticipantExamResult::query()
            ->where('exam_session_id', $this->examSessionId)
            ->leftJoin('participants', 'participants.id', '=', 'participant_exam_results.participant_id')
            ->select(['participant_exam_results.*', 'participants.name as participant_name']);
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
            ->addColumn('participant', fn (ParticipantExamResult $model) => BootstrapElementsService::badge(
                color: 'primary',
                type: 'a',
                child: e($model->participant_name),
                attributes: [
                    'href' => route('participants.show', $model->participant_id),
                ]
            ))
            ->addColumn('answer_count', fn (ParticipantExamResult $model) => sprintf(
                '%s / %s / %s',
                $model->correct_answer_count,
                $model->wrong_answer_count,
                $model->unanswered_count
            ))
            ->addColumn('total_score')
            ->addColumn('is_passed', fn (ParticipantExamResult $model) => $model->finished_at ? ($model->is_passed ? BootstrapElementsService::badge(
                color: 'success',
                type: 'span',
                child: 'Lulus',
            ) : BootstrapElementsService::badge(
                color: 'danger',
                type: 'span',
                child: 'Tidak Lulus',
            )) : BootstrapElementsService::badge(
                color: 'dark',
                type: 'span',
                child: 'Belum Selesai',
            ))
            ->addColumn('started_at')
            ->addColumn('started_at_formatted', fn (ParticipantExamResult $model) => $model->started_at->translatedFormat('d M Y H:i'))
            ->addColumn('finished_at')
            ->addColumn('finished_at_formatted', fn (ParticipantExamResult $model) => $model->finished_at?->translatedFormat('d M Y H:i'));
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

            Column::make('Peserta', 'participant', 'participants.name')
                ->sortable()
                ->searchable(),

            Column::make('Total Jawaban (B/S/-)', 'answer_count'),

            Column::make('Total Nilai', 'total_score')
                ->searchable()
                ->sortable(),

            Column::make('Status Lulus', 'is_passed')->sortable(),

            Column::make('Waktu Mulai', 'started_at_formatted', 'started_at')
                ->sortable(),
            Column::make('Selesai Pada', 'finished_at_formatted', 'finished_at')
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
            Filter::datepicker('started_at', 'participant_exam_results.started_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'participant_exam_results.started_at')
            ),
            Filter::datepicker('finished_at', 'participant_exam_results.finished_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'participant_exam_results.finished_at')
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
     * PowerGrid ParticipantExamResult Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(ParticipantExamResult $participantExamResult): array
    {
        return [
            Button::make('result', 'Hasil')
                ->class('badge text-dark-emphasis bg-dark-subtle border border-dark-subtle')
                ->route('exam-sessions.result', [
                    'exam_session' => $this->examSessionId,
                    'participant_exam_result' => $participantExamResult->id
                ])->target(''),
        ];
    }
}
