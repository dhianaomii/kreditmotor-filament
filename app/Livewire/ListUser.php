<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserForm;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;



class ListUser extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => [],
            'marketing' => [],
            'kurir' => [],
        ];
    }

    public function can(string $action): bool
    {
        $user = auth()->user();
        $permissions = $this->getRolePermissions();
        
        return in_array($action, $permissions[$user->role] ?? []);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'primary',
                        'ceo' => 'success',
                        'marketing' => 'warning',
                        'kurir' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)

            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(UserForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(UserForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(User::class)
                    ->form(UserForm::schema())
                    ->visible(fn () => $this->can('create'))
            ]);
    }

    public function render()
    {
        return view('livewire.list-user');
    }
}