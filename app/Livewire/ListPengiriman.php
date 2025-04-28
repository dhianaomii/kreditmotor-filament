<?php

namespace App\Livewire;

use App\Models\Pengirimans;
use App\Services\PengirimanForm;
use Dom\Text;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Livewire\Component;
use Filament\Tables\Actions\ActionGroup;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;


class ListPengiriman extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => ['view'], // CEO can only view
            'marketing' => [], // Marketing can't delete
            'kurir' => ['view', 'edit'], // Kurir has no access
            
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
        return view('livewire.list-pengiriman');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Pengirimans::query())
            ->columns([
                TextColumn::make('no_invoice')
                    ->label('No Invoice'),
                TextColumn::make('kredit_id')
                    ->label('ID Kredit'),
                TextColumn::make('tgl_kirim')
                    ->label('Tanggal Kirim'),   
                TextColumn::make('tgl_tiba')
                    ->label('Tanggal Tiba'),
                TextColumn::make('status_kirim')
                    ->label('Status Kirim'),
                TextColumn::make('nama_kurir')
                    ->label('Nama Kurir'),
                TextColumn::make('telpon_kurir')
                    ->label('Telpon Kurir'),
                TextColumn::make('keterangan')
                    ->label('Keterangan'),
                ImageColumn::make('bukti_foto')
                    ->label('Bukti Foto')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100)
                    ->height(100),
            ])
            //->actionsColumnLabel('Actions')
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            //->sortable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(PengirimanForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(PengirimanForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Pengirimans::class)
                    ->form(PengirimanForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                //
            ]);
    }
}
