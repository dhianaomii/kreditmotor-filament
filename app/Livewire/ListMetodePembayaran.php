<?php

namespace App\Livewire;

use App\Models\MetodePembayaran;
use App\Services\MetodePembayaranForm;
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


class ListMetodePembayaran extends Component implements HasTable, HasForms
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
        return view('livewire.list-metode-pembayaran');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MetodePembayaran::query())
            ->columns([
                TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran'),
                TextColumn::make('tempat_bayar')
                    ->label('Tempat Bayar'),
                TextColumn::make('no_rekening')
                    ->label('No Rekening'),
                ImageColumn::make('logo')
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
                        ->form(MetodePembayaranForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(MetodePembayaranForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(MetodePembayaran::class)
                    ->form(MetodePembayaranForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                //
            ]);
    }
}
