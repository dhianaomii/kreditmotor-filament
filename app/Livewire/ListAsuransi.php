<?php

namespace App\Livewire;

use App\Models\Asuransi;
use App\Services\AsuransiForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
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

class ListAsuransi extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => [], // CEO has no access to asuransi
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
        return view('livewire.list-asuransi'); // Fixed view name
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Asuransi::query())
            ->columns([
                TextColumn::make('nama_perusahaan_asuransi')
                    ->label('Perusahaan Asuransi'),
                TextColumn::make('nama_asuransi')
                    ->label('Asuransi'),
                TextColumn::make('no_rekening')
                    ->label('No Rekening'),
                TextColumn::make('margin_asuransi')
                    ->suffix('%')
                    ->label('Margin Asuransi'),
                ImageColumn::make('logo')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')  
                    ->width(100)
                    ->height(100),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(AsuransiForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(AsuransiForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Asuransi::class)
                    ->form(AsuransiForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                // No bulk actions for now
            ]);
    }
}