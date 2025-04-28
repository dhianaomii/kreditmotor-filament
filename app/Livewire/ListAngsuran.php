<?php

namespace App\Livewire;

use App\Models\Angsuran;
use App\Services\AngsuranForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Livewire\Component;

class ListAngsuran extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => ['view'],
            'marketing' => ['view', 'create', 'edit'],
            'kurir' => [],
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
        return view('livewire.list-angsuran');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Angsuran::query())
            ->columns([
                TextColumn::make('kredit.pengajuanKredit.pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan'),
                TextColumn::make('tgl_bayar')
                    ->date()
                    ->label('Tanggal Bayar'),
                TextColumn::make('angsuran_ke')
                    ->label('Angsuran Ke')
                    ->prefix('Angsuran ke - '),
                TextColumn::make('total_bayar')
                    ->money('IDR')
                    ->label('Total Bayar'),
                TextColumn::make('keterangan')
                    ->label('Keterangan'),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(AngsuranForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(AngsuranForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Angsuran::class)
                    ->form(AngsuranForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                // No bulk actions for now
            ]);
    }
}