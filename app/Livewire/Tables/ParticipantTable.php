<?php

namespace App\Livewire\Tables;

use App\Models\Participant;
use App\Services\BootstrapElementsService;
use App\Services\PowerGridTableService;
use App\Services\StorageService;
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

final class ParticipantTable extends PowerGridComponent
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
            $participantPictures = Participant::whereNot('picture', 'participant-pictures/default.jpg')->whereIn('id', $ids)->pluck('picture');

            Participant::whereIn('id', $ids)->delete();

            StorageService::public()->getStorage()->delete($participantPictures);
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
     * @return Builder<\App\Models\Participant>
     */
    public function datasource(): Builder
    {
        return Participant::query();
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
            ->addColumn('nik', fn (Participant $model) => BootstrapElementsService::badge(
                color: 'primary',
                type: 'span',
                child: e($model->nik)
            ))
            ->addColumn('email', fn (Participant $model) => BootstrapElementsService::badge(
                color: 'success',
                type: 'a',
                child: e($model->email),
                attributes: [
                    'href' => 'mailto:' . $model->email
                ]
            ))
            ->addColumn('name')
            ->addColumn('address', fn (Participant $model) => e(str($model->address)->words(6)))
            ->addColumn(
                'gender',
                fn (Participant $model) => $model->isMale() ? BootstrapElementsService::badge(
                    color: 'primary',
                    type: 'span',
                    child: 'Laki-Laki'
                ) : BootstrapElementsService::badge(
                    color: 'success',
                    type: 'span',
                    child: 'Perempuan'
                )
            )
            ->addColumn(
                'status',
                fn (Participant $model) => $model->user_id ? BootstrapElementsService::badge(
                    color: 'success',
                    type: 'a',
                    child: 'Aktif',
                    attributes: ['href' => route('users.show', $model->user_id)],
                ) : BootstrapElementsService::badge(
                    color: 'danger',
                    type: 'span',
                    child: 'Tidak Aktif'
                )
            )
            ->addColumn('created_at')
            ->addColumn(
                'created_at_formatted',
                fn (Participant $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s')
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

            Column::make('Nomor Peserta', 'nik')
                ->searchable()
                ->sortable(),

            Column::make('Email', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Nama Lengkap', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Alamat', 'address')
                ->searchable()
                ->sortable(),

            Column::make('Jenis Kelamin', 'gender')->sortable(),

            Column::make('Status Aktif', 'status', 'user_id')->sortable(),

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
            Filter::select('gender', 'gender')
                ->dataSource([
                    [
                        'label' => 'Laki-Laki',
                        'value' => '0',
                    ],
                    [
                        'label' => 'Perempuan',
                        'value' => '1',
                    ],
                ])
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::datepicker('created_at', 'participants.created_at')->builder(
                fn (Builder $query, mixed $value) => PowerGridTableService::datepicker($query, $value, 'participants.created_at')
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
     * PowerGrid Participant Action Buttons.
     *
     * @return array<int, Button>
     */
    public function actions(Participant $participant): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('badge text-warning-emphasis bg-warning-subtle border border-warning-subtle')
                ->route('participants.edit', ['participant' => $participant->id])->target(''),
            Button::make('detail', 'Detail')
                ->class('badge text-success-emphasis bg-success-subtle border border-success-subtle')
                ->route('participants.show', ['participant' => $participant->id])->target(''),
            Button::make('access', 'Akses Ujian')
                ->class('badge text-info-emphasis bg-info-subtle border border-info-subtle')
                ->route('participants.access', ['participant' => $participant->id])->target(''),
        ];
    }
}
