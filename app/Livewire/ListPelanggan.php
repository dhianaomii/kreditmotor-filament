<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use App\Services\PelangganForm;
use Dom\Text;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
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


class ListPelanggan extends Component implements HasTable, HasForms
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
        return view('livewire.list-pelanggan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Pelanggan::query())
            ->columns([
                BooleanColumn::make('is_blocked')
                    ->label('Status Blokir')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueIcon('heroicon-o-check-circle'),
                TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('no_hp')
                    ->label('No HP'),
                TextColumn::make('alamat1')
                    ->label('Alamat'),
                TextColumn::make('kota1')
                    ->label('Kota'),
                TextColumn::make('provinsi1')
                    ->label('Provinsi'),
                TextColumn::make('kode_pos1')
                    ->label('Kode Pos'),
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')  
                    ->width(100)
                    ->height(100),
            ])
            //->actionsColumnLabel('Actions')
            ->actionsPosition(ActionsPosition::BeforeCells)
            //->sortable()
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(PelangganForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(PelangganForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Pelanggan::class)
                    ->form(PelangganForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                //
            ]);
    }
}
