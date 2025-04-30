<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Services\BlogForm;
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

class ListBlog extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'ceo' => [], // CEO has no access to asuransi
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
        return view('livewire.list-blog'); // Fixed view name
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Blog::query())
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Content')
                    ->limit(10),
                TextColumn::make('content')
                    ->label('Content')
                    ->limit(15),
                TextColumn::make('sumber')
                    ->label('Sumber Content'),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('publish_at')
                    ->label('Tanggal Penerbitan'),
                TextColumn::make('User.name')
                    ->label('Penulis Blog'),
                ImageColumn::make('image')
                    ->label('Foto')
                    // ->disk('public')
                    // ->directory('blog')
                    // ->visibility('public')  
                    ->width(100)
                    ->height(100),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(BlogForm::schema())
                        ->visible(fn () => $this->can('view')),
                        
                    EditAction::make('edit')
                        ->form(BlogForm::schema())
                        ->visible(fn () => $this->can('edit')),
                        
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Blog::class)
                    ->form(BlogForm::schema())
                    ->visible(fn () => $this->can('create'))
            ])
            ->bulkActions([
                // No bulk actions for now
            ]);
    }
}