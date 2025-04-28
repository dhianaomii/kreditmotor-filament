<?php

namespace App\Livewire;

use App\Models\Kredit;
use App\Services\KreditForm;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;

class ListKredit extends Component implements HasTable, HasForms
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
        return view('livewire.list-kredit');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Kredit::query())
            ->columns([
                TextColumn::make('pengajuanKredit.pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('MetodePembayaran.metode_pembayaran')
                    ->label('Metode Pembayaran'),
                TextColumn::make('tgl_mulai_kredit')
                    ->label('Tanggal Mulai Kredit'),
                TextColumn::make('tgl_selesai_kredit')
                    ->label('Tanggal Selesai Kredit'),
                ImageColumn::make('url_bukti_bayar')
                    ->label('Bukti Bayar DP'),
                TextColumn::make('status_kredit')
                    ->label('Status Kredit'),
                TextColumn::make('keterangan_status_kredit')
                    ->label('Keterangan Status Kredit'),
            ])
            ->actionsPosition(ActionsPosition::BeforeCells)
            ->searchable()
            ->actions([
                ActionGroup::make([
                    ViewAction::make('view')
                        ->form(KreditForm::schema())
                        ->visible(fn () => $this->can('view')),
                    EditAction::make('edit')
                        ->form(KreditForm::schema())
                        ->visible(fn () => $this->can('edit')),
                    DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->visible(fn () => $this->can('delete'))
                ])
                ->dropdownPlacement('top-start')
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Kredit::class)
                    ->form(KreditForm::schema())
                    ->visible(fn () => $this->can('create')),
                    ExportAction::make('export_excel')
                    ->label('Export to Excel')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename('kredit_export_' . date('Y-m-d'))
                    ])
                    ->visible(fn () => $this->can('view')),
                Action::make('export_pdf')
                    ->label('Export to PDF')
                    ->action(function () {
                        $kredits = Kredit::all(); 
                        $pdf = Pdf::loadView('pdf.kredit', ['kredits' => $kredits]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'kredit_export_' . date('Y-m-d') . '.pdf'
                        );
                    })
                    ->visible(fn () => $this->can('view'))
            ])
            ->bulkActions([
                //
        ]);
    }
}
