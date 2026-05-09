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
            'site_name' => Setting::get('site_name', 'MR Lux Indonesia'),
            'site_website' => Setting::get('site_website', 'www.mrluxindonesia.com'),
            'site_phone' => Setting::get('site_phone', '(024) 7624836'),
            'site_email' => Setting::get('site_email', 'info@mrluxindonesia.com'),
            'site_address' => Setting::get('site_address', 'Semarang, Indonesia'),
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'ppn_percentage' => Setting::get('ppn_percentage', 11),
        ];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil Perusahaan')
                    ->description('Informasi ini akan muncul di dashboard, laporan, dan nota cetak.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama Perusahaan')
                            ->required(),
                        TextInput::make('site_website')
                            ->label('Website')
                            ->url(),
                        TextInput::make('site_phone')
                            ->label('Nomor Telepon'),
                        TextInput::make('site_email')
                            ->label('Email')
                            ->email(),
                        \Filament\Forms\Components\Textarea::make('site_address')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\FileUpload::make('site_logo')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->directory('settings')
                            ->visibility('public'),
                        \Filament\Forms\Components\FileUpload::make('site_favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('settings')
                            ->visibility('public')
                            ->helperText('Favicon akan muncul di tab browser. Gunakan file .ico atau .png kecil.'),
                    ])->columns(2),

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
