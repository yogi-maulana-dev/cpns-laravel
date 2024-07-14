<?php

namespace App\Livewire\Tables\Participant;

use App\Models\ExamSession;
use App\Services\PowerGridTableService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class HistoryExamSessionTable extends PowerGridComponent
{
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        return [
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
     * @return Builder<\App\Models\ExamSession>
     */
    public function datasource(): Builder
    {
        return ExamSession::query()
            ->forMe()
            ->finished();
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
            ->addColumn('code')
            ->addColumn('name')
            ->addColumn('time')
            ->addColumn('start_at')
            ->addColumn('end_at')
            ->addColumn('start_at_end_at', fn (ExamSession $model) => sprintf('%s - %s', $model->start_at->format('d M Y H:i'), $model->end_at->format('d M Y H:i')))
            ->addColumn('created_at')
            ->addColumn(
                'created_at_formatted',
                fn (ExamSession $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s')
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

            Column::make('Nomor Sesi', 'code')
                ->searchable(),

            Column::make('Nama Sesi Ujian', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Waktu (Menit)', 'time')
                ->searchable()
                ->sortable(),

            Column::make('start_at', 'start_at')
                ->hidden(),

            Column::make('end_at', 'end_at')
                ->hidden(),

            Column::make('Tanggal & Waktu Ujian', 'start_at_end_at'),

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
            Filter::datepicker('created_at', 'exam_sessions.created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'exam_sessions.created_at')
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
     * PowerGrid ExamSession Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(ExamSession $examSession): array
    {
        return [
            Button::make('detail', 'Detail Ujian')
                ->class('badge text-primary-emphasis bg-primary-subtle border border-primary-subtle')
                ->route('me.exam-sessions.show', ['exam_session' => $examSession->code])->target(''),
            Button::make('result', 'Hasil')
                ->class('badge text-success-emphasis bg-success-subtle border border-success-subtle')
                ->route('me.exam-sessions.result', ['exam_session' => $examSession->code])->target(''),
        ];
    }
}
