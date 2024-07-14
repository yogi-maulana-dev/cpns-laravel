<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;

class PowerGridTableService
{
    public static function getListeners(array $listeners): array
    {
        return array_merge(
            $listeners,
            ['bulkCheckedDelete']
        );
    }

    public static function getHeader(): array
    {
        // tombol hapus ada 2 dibawah hanya untuk menampilkan comfirn alert
        return [
            Button::add('bulk-checked')
                ->slot(__('Hapus'))
                ->class('btn btn-danger border-0 d-none target-powergrid-delete-button')
                ->dispatch('bulkCheckedDelete', []),
            Button::add('bulk-checked')
                ->slot(__('Hapus'))
                ->class('btn btn-danger btn-sm fw-bold mt-1 border-0 powergrid-delete-button')
                ->route('dashboard.index', []),
        ];
    }

    public static function bulkCheckedDelete(
        \PowerComponents\LivewirePowerGrid\PowerGridComponent $this_,
        callable $callback,
        string $message = null,
        string $failedMessage = null
    ) {
        if (auth()->check()) {
            $ids = $this_->checkedValues();

            if (!$ids) {
                return $this_->dispatch(
                    'show-toast',
                    [
                        'success' => false,
                        'message' => 'Pilih data yang ingin dihapus terlebih dahulu.',
                    ]
                );
            }

            try {

                $callback($ids);

                $this_->dispatch('show-toast', [
                    'success' => true,
                    'message' => $message ?? 'Data berhasil dihapus.',
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                $this_->dispatch('show-toast', [
                    'success' => false,
                    'message' => $failedMessage ?? 'Data gagal dihapus, kemungkinan ada data lain yang menggunakan data tersebut.',
                ]);
            } catch (\App\Exceptions\PowergridException $ex) {
                $this_->dispatch('show-toast', [
                    'success' => false,
                    'message' => $ex->getMessage() ?? 'POWERGRID_EXCEPTION',
                ]);
            }
        }
    }

    public static function datepicker(Builder $query, mixed $value, string $field)
    {
        $startDate = $value[0] instanceof Carbon ? $value[0] : Carbon::parse($value[0]);
        $endDate = $value[1] instanceof Carbon ? $value[1] : Carbon::parse($value[1]);

        // if ($startDate->format('d-m-Y') !== $endDate->format('d-m-Y')) {
        //     $startDate = $startDate->addDay();
        //     $endDate = $endDate->addDay();
        // }
        $startDate = $startDate->addDay();
        $endDate = $endDate->addDay();

        return $query
            ->whereDate($field, '>=', $startDate)->whereDate($field, '<=', $endDate);
    }
}
