<?php

namespace App\Livewire\Tables;

use App\Models\LogLogin;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Rules\RuleActions;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class LogLoginTable extends PowerGridComponent
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
            LogLogin::whereIn('id', $ids)->delete();
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
     * @return Builder<\App\Models\LogLogin>
     */
    public function datasource(): Builder
    {
        return LogLogin::query()
            ->leftJoin('users', 'log_logins.user_id', '=', 'users.id')
            ->select(
                'log_logins.*',
                'users.name as user'
            );
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
            ->addColumn('subject')
            ->addColumn('ip', function (LogLogin $model) {
                return BootstrapElementsService::badge(
                    'primary',
                    'span',
                    e($model->ip)
                );
            })
            ->addColumn('agent')
            ->addColumn('user', function (LogLogin $model) {
                return $model->user_id ?
                    BootstrapElementsService::badge(
                        'primary',
                        'a',
                        e($model->user),
                        ['href' => route('users.show', $model->user_id)]
                    ) : BootstrapElementsService::badge(
                        'danger',
                        'span',
                        'Tidak Ada'
                    );
            })
            ->addColumn('logged_in_at')
            ->addColumn('logged_in_at_formatted', fn (LogLogin $model) => Carbon::parse($model->logged_in_at)->format('d/m/Y H:i:s'))

            ->addColumn('created_at')
            ->addColumn(
                'created_at_formatted',
                fn (LogLogin $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s')
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

            Column::make('Subject', 'subject')
                ->searchable()
                ->sortable(),

            Column::make('IP Address', 'ip')
                ->searchable()
                ->sortable(),

            Column::make('Agent / Perangkat', 'agent')
                ->searchable()
                ->sortable(),

            Column::make('User', 'user')
                ->searchable()
                ->sortable(),

            Column::make('Login Pada', 'logged_in_at_formatted', 'logged_in_at')
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
            Filter::inputText('user', 'users.name'),
            Filter::datepicker('logged_in_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'logged_at_in')
            ),
            Filter::datepicker('created_at', 'log_logins.created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'log_logins.created_at')
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
     * PowerGrid LogLogin Action Buttons.
     *
     * @return array<int, Button>
     */

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('log-login.edit', ['log-login' => 'id']),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('log-login.destroy', ['log-login' => 'id'])
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid LogLogin Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($log-login) => $log-login->id === 1)
                ->hide(),
        ];
    }
    */
}
