<?php

namespace App\Livewire;

use App\Models\PengajuanKredit;
use App\Models\Motor;
use App\Services\PengajuanKreditForm;
use Dom\Text;
// use Filament\Actions\ViewAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction; 
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Livewire\Component;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\DB;
class ListPengajuanKredit extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;
    
    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => ['view'], // CEO can only view
            'marketing' => ['view', 'create', 'edit'], // Marketing can't delete
            'kurir' => [], // Kurir has no access
            
        ];
    }

    public function can(string $action): bool
    {
        $user = auth()->user();
        $permissions = $this->getRolePermissions();
        
        return in_array($action, $permissions[$user->role] ?? []);
    }

    public function render()
    {
        return view('livewire.list-pengajuan-kredit');
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(PengajuanKredit::query())
            ->columns([
                TextColumn::make('status_pengajuan')
                    ->label('Status Pengajuan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Konfirmasi' => 'warning',
                        'Diproses' => 'warning',
                        'Dibatalkan Pembeli' => 'danger',
                        'Dibatalkan Penjual' => 'danger',
                        'Bermasalah' => 'danger',
                        'Diterima' => 'success',
                        'Menunggu Pembayaran' => 'warning',
                        default => 'secondary',
                    })
                    ->alignCenter(),

                TextColumn::make('Pelanggan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('Motor.nama_motor')
                    ->label('Motor')
                    ->alignCenter(),

                TextColumn::make('harga_kredit')
                    ->money('IDR')
                    ->label('Harga Kredit')
                    ->alignCenter(),

                TextColumn::make('harga_jualh')
                    ->money('IDR')
                    ->label('Harga Cash')
                    ->alignCenter()
                    ->hidden(),

                TextColumn::make('dp')
                    ->money('IDR')
                    ->label('DP')
                    ->alignCenter(),

                TextColumn::make('JenisCicilan.lama_cicilan')
                    ->label('Lama Cicilan')
                    ->alignCenter()
                    ->suffix(' bulan'),


                TextColumn::make('Asuransi.nama_asuransi')
                    ->label('Asuransi')
                    ->alignCenter(),

                TextColumn::make('biaya_asuransi_perbulan')
                    ->money('IDR')
                    ->label('Biaya Asuransi Perbulan')
                    ->alignCenter()
                    ->hidden(),

                TextColumn::make('cicilan_perbulan')
                    ->money('IDR')
                    ->label('Cicilan Perbulan')
                    ->alignCenter(),
                    // ->color('primary'),

                TextColumn::make('tgl_pengajuan_kredit')
                    ->label('Tanggal Pengajuan Kredit')
                    ->alignCenter(),

                TextColumn::make('keterangan_status_pengajuan')
                    ->label('Keterangan Status Pengajuan')
                    ->alignCenter()
                    ->hidden(),

                ImageColumn::make('url_kk')
                    ->label('Kartu Keluarga')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
                    ->circular()
                    ->alignCenter()
                    ->hidden(),

                ImageColumn::make('url_ktp') 
                    ->label('KTP')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
                    ->circular()
                    ->alignCenter()
                    ->hidden(),

                ImageColumn::make('url_npwp') 
                    ->label('NPWP')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
                    ->circular()
                    ->alignCenter()
                    ->hidden(),

                ImageColumn::make('url_slip_gaji') 
                    ->label('Slip Gaji')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
                    ->circular()
                    ->alignCenter()
                    ->hidden(),
                    
                ImageColumn::make('url_foto')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
                    ->circular()
                    ->alignCenter(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(PengajuanKreditForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(PengajuanKreditForm::schema())
                        ->visible(fn () => $this->can('edit'))
                        ->after(function (PengajuanKredit $record, array $data) {
                            $motor = Motor::find($record->motor_id);

                            // Jika status diubah ke "Menunggu Konfirmasi" atau "Diproses"
                            if (in_array($data['status_pengajuan'], ['Menunggu Konfirmasi', 'Diproses']) && $record->is_stock_returned) {
                                if ($motor->stok <= 0) {
                                    throw new \Exception('Stok motor tidak cukup untuk mengubah status ke ' . $data['status_pengajuan']);
                                }
                                DB::transaction(function () use ($record, $motor) {
                                    $motor->stok -= 1;
                                    $motor->save();
                                    $record->is_stock_returned = false;
                                    $record->save();
                                });
                            }
                            // Jika status diubah ke "Dibatalkan Pembeli" atau "Ditolak"
                            elseif (in_array($data['status_pengajuan'], ['Dibatalkan Pembeli', 'Ditolak', 'Bermasalah', 'Ditolak Penjual']) && !$record->is_stock_returned) {
                                DB::transaction(function () use ($record, $motor) {
                                    $motor->stok += 1;
                                    $motor->save();
                                    $record->is_stock_returned = true;
                                    $record->save();
                                });
                            }
                        }),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(PengajuanKredit::class)
                    ->form(PengajuanKreditForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            //->actionsColumnLabel('Actions')
            ->actionsPosition(ActionsPosition::BeforeCells)
            // ->actionsAlignment('center') // Opsi: 'left', 'center', atau 'right'
            ->searchable()
            //->sortable()
            ->bulkActions([
                //
            ]);
    }

}
