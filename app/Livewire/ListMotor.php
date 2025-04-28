<?php

namespace App\Livewire;

use App\Models\Motor;
use App\Services\MotorForm;
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


class ListMotor extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => [], // CEO can only view
            'marketing' => [], // Marketing can't delete
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
        return view('livewire.list-motor');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Motor::query())
            ->columns([
                TextColumn::make('nama_motor')
                    ->label('Nama Motor'),
                    // ->searchable()
                    // //->sortable(),
                TextColumn::make('JenisMotor.jenis')
                    ->label('Jenis Motor'),
                TextColumn::make('harga_jual')
                    ->money('IDR')
                    ->label('Harga Jual'),
                    // ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ',')),
                TextColumn::make('deskripsi_motor')
                    ->label('Deskripsi'), 
                TextColumn::make('warna')
                    ->alignCenter()
                    ->label('Warna'),
                TextColumn::make('kapasitas_mesin')
                    ->alignCenter()
                    ->label('Kapasitas Mesin'),
                TextColumn::make('tahun_produksi')
                    ->alignCenter()
                    ->label('Tahun Produksi'),  
                TextColumn::make('stok')
                    ->alignCenter()
                    ->label('Stok'),
                ImageColumn::make('foto1')
                    ->label('Foto 1')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100),
                ImageColumn::make('foto2')
                    ->label('Foto 2')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100),
                ImageColumn::make('foto3')
                    ->label('Foto 3')
                    ->disk('public')
                    ->visibility('public')
                    ->width(100) 
                    ->height(100)
            ])
            //->actionsColumnLabel('Actions')
            ->actionsPosition(ActionsPosition::BeforeCells)
            //->sortable()
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(MotorForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(MotorForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Motor::class)
                    ->form(MotorForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                //
            ]);
    }
}
