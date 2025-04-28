<?php

namespace App\Livewire;

use App\Models\JenisCicilan;
use App\Services\JenisCicilanForm;
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

class ListJenisCicilan extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => [], // CEO can only view
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
        return view('livewire.list-jenis-cicilan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(JenisCicilan::query())
            ->columns([
                TextColumn::make('jenis_cicilan')
                    ->label('Jenis Cicilan'),
                TextColumn::make('lama_cicilan')
                    ->label('Lama Cicilan'),
                TextColumn::make('margin_kredit')
                    ->suffix('%')
                    ->label('Margin Kredit'),
                TextColumn::make('dp')
                    ->label('DP')
                    ->suffix('%'),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(JenisCicilanForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(JenisCicilanForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(JenisCicilan::class)
                    ->form(JenisCicilanForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                // No bulk actions for now
            ]);
    }
}