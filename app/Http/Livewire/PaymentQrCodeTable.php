<?php

namespace App\Http\Livewire;

use App\Models\PaymentQrCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentQrCodeTable extends LivewireTableComponent
{
    protected $model = PaymentQrCode::class;

    protected string $tableName = 'payment_qr_codes';

    // for table header button
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'payment_qr_codes.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return [
                    'style' => 'width:9%;text-align:center',
                ];
            }

            return [
                // 'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->getField() === 'title') {
                return [
                    'class' => 'w-50',
                ];
            }
            if ($column->getField() === 'created_at') {
                return [
                    'class' => 'w-25',

                ];
            }
            if ($column->getField() === 'is_default') {
                return [
                    // 'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.payment_qr_codes.title'), 'title')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.payment_qr_codes.qr_image'), 'created_at')
                ->view('payment_qr_codes.components.qr_code'),
            Column::make(__('messages.payment_qr_codes.default'), 'is_default')
                ->view('payment_qr_codes.components.default'),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->withValue([
                            'data-id' => $row->id,
                            'data-edit-id' => 'qrcode-edit-btn',
                            'data-delete-id' => 'qrcode-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        $user = Auth::user();
        return PaymentQrCode::query()->select('payment_qr_codes.*')->where('user_id', $user->id);
    }

    public function resetPageTable()
    {
        $this->customResetPage('payment_qr_codesPage');
    }
}
