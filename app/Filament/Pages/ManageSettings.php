<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Sistem';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'ppn_percentage' => Setting::get('ppn_percentage', 11),
        ];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pajak & Keuangan')
                    ->description('Atur persentase pajak dan parameter keuangan lainnya.')
                    ->schema([
                        TextInput::make('ppn_percentage')
                            ->label('Persentase PPN (%)')
                            ->numeric()
                            ->suffix('%')
                            ->required()
                            ->helperText('Persentase ini akan digunakan sebagai default kalkulasi PPN di form Penjualan.'),
                    ])
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            foreach ($data as $key => $value) {
                Setting::set($key, $value);
            }

            Notification::make()
                ->title('Pengaturan berhasil disimpan')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan')
                ->danger()
                ->send();
        }
    }
}
