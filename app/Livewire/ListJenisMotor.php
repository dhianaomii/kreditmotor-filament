<?php

namespace App\Livewire;

use App\Models\JenisMotor;
use App\Services\JenisMotorForm;
// use Dom\Text;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
// use Filament\Forms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Livewire\Component;
// use PhpParser\Node\Stmt\Label;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;


class ListJenisMotor extends Component implements HasTable, HasForms
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
        return view('livewire.list-jenis-motor');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(JenisMotor::query())
            ->columns([
                TextColumn::make('id')
                    ->Label('No'),
                TextColumn::make('merk')
                    ->label('Merk'),
                TextColumn::make('jenis')
                    ->label('Jenis'),
                TextColumn::make('deskripsi_jenis')
                    ->label('Deskripsi'),
                ImageColumn::make('image')
                    ->label('Foto')
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
                        ->form(JenisMotorForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(JenisMotorForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(JenisMotor::class)
                    ->form(JenisMotorForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                //
            ]);
    }
}
