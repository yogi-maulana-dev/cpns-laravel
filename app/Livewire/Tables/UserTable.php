<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\User;
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

final class UserTable extends PowerGridComponent
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
            if (in_array(auth()->id(), $ids)) {
                return $this->dispatch(
                    'show-toast',
                    [
                        'success' => false,
                        'message' => 'Anda tidak diperbolehkan menghapus data user anda sendiri, Coba login dengan akun lain, lalu hapus data yang ingin dihapus.',
                    ]
                );
            }

            User::whereIn('id', $ids)->delete();
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
            Header::make()->showSearchInput(),
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
     * @return Builder<\App\Models\User>
     */
    public function datasource(): Builder
    {
        return User::query();
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
            ->addColumn('email')
            ->addColumn('role', function (User $model) {
                return match ($model->role) {
                    UserRole::SUPERADMIN() => BootstrapElementsService::badge(
                        'primary',
                        'span',
                        'Superadmin'
                    ),
                    UserRole::OPERATOR_UJIAN() => BootstrapElementsService::badge(
                        'success',
                        'span',
                        'Operator Ujian'
                    ),
                    UserRole::OPERATOR_SOAL() => BootstrapElementsService::badge(
                        'warning',
                        'span',
                        'Operator Soal'
                    ),
                    UserRole::PARTICIPANT() => BootstrapElementsService::badge(
                        'info',
                        'span',
                        'Peserta'
                    ),
                    default => '-'
                };
            })

            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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

            Column::make('Nama', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Email', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Hak Level', 'role')
                ->searchable()
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
            Filter::multiSelect('role')
                ->dataSource([
                    ['label' => 'Superadmin', 'role' => UserRole::SUPERADMIN()],
                    ['label' => 'Operator Ujian', 'role' => UserRole::OPERATOR_UJIAN()],
                    ['label' => 'Operator Soal', 'role' => UserRole::OPERATOR_SOAL()],
                    ['label' => 'Peserta', 'role' => UserRole::PARTICIPANT()],
                ])
                ->optionLabel('label')
                ->optionValue('role'),
            Filter::datepicker('created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'created_at')
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
     * PowerGrid User Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(User $user): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('badge text-warning-emphasis bg-warning-subtle border border-warning-subtle')
                ->route('users.edit', ['user' => $user->id])->target(''),
            Button::make('detail', 'Detail')
                ->class('badge text-success-emphasis bg-success-subtle border border-success-subtle')
                ->route('users.show', ['user' => $user->id])->target(''),
        ];
    }
}
