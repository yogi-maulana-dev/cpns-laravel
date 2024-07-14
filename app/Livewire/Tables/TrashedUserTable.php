<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\Participant;
use App\Models\User;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use Illuminate\Database\Eloquent\Builder;
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

final class TrashedUserTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'bulkCheckedForceDelete',
                'bulkCheckedRestore',
            ]
        );
    }

    public function header(): array
    {
        // tombol hapus ada 2 dibawah hanya untuk menampilkan comfirn alert

        return array_merge([
            Button::add('bulk-checked')
                ->slot(__('Hapus Permanen'))
                ->class('btn btn-danger border-0 d-none target-powergrid-force-delete-button')
                ->dispatch('bulkCheckedForceDelete', []),
            Button::add('bulk-checked')
                ->slot(__('Hapus Permanen'))
                ->class('btn btn-danger fw-bold btn-sm mt-1 border-0 powergrid-force-delete-button')
                ->route('users.index', []),
            Button::add('bulk-checked')
                ->slot(__('Restore'))
                ->class('btn btn-success fw-bold btn-sm mt-1 border-0')
                ->dispatch('bulkCheckedRestore', []),
        ]);
    }

    public function bulkCheckedForceDelete()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids) {
                return $this->dispatch(
                    'show-toast',
                    [
                        'success' => false,
                        'message' => 'Pilih data yang ingin dihapus terlebih dahulu.',
                    ]
                );
            }

            if (in_array(auth()->id(), $ids)) {
                return $this->dispatch(
                    'show-toast',
                    [
                        'success' => false,
                        'message' => 'Anda tidak diperbolehkan menghapus data user anda sendiri, Coba login dengan akun lain, lalu hapus data yang ingin dihapus.',
                    ]
                );
            }

            try {
                DB::beginTransaction();
                $userIds = User::onlyTrashed()
                    ->whereIn('id', $ids)
                    ->where('role', UserRole::PARTICIPANT())
                    ->pluck('id');
                Participant::whereIn('user_id', $userIds)->update([
                    'user_id' => null,
                ]);
                User::onlyTrashed()->whereIn('id', $userIds)->forceDelete();

                $this->dispatch('show-toast', [
                    'success' => true,
                    'message' => 'Data user berhasil dihapus permanen.',
                ]);
                DB::commit();
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();

                $this->dispatch('show-toast', [
                    'success' => false,
                    'message' => 'Data gagal dihapus, kemungkinan ada data lain yang menggunakan data tersebut.',
                ]);
            }
        }
    }

    public function bulkCheckedRestore()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids) {
                return $this->dispatch(
                    'show-toast',
                    [
                        'success' => false,
                        'message' => 'Pilih data yang ingin direstore terlebih dahulu.',
                    ]
                );
            }

            User::onlyTrashed()->whereIn('id', $ids)->restore();

            $this->dispatch('show-toast', [
                'success' => true,
                'message' => 'Data user berhasil direstore kembali.',
            ]);
        }
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
        return User::query()->onlyTrashed();
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
            Filter::select('role')
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
