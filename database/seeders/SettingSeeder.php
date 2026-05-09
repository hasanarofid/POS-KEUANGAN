<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'          => 'site_name',
                'value'        => 'POS KEUANGAN',
                'display_name' => 'Nama Sistem',
                'type'         => 'text',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_logo',
                'value'        => 'images/logo-default.png',
                'display_name' => 'Logo',
                'type'         => 'image',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_favicon',
                'value'        => 'images/logo-default.png',
                'display_name' => 'Favicon',
                'type'         => 'image',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_url',
                'value'        => 'hasanarofid.site',
                'display_name' => 'Website',
                'type'         => 'text',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_email',
                'value'        => 'info@hasanarofid.site',
                'display_name' => 'Email',
                'type'         => 'text',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_phone',
                'value'        => '',
                'display_name' => 'Telepon',
                'type'         => 'text',
                'group'        => 'general',
            ],
            [
                'key'          => 'site_address',
                'value'        => '',
                'display_name' => 'Alamat',
                'type'         => 'textarea',
                'group'        => 'general',
            ],
            [
                'key'          => 'ppn_rate',
                'value'        => '11',
                'display_name' => 'Tarif PPN (%)',
                'type'         => 'number',
                'group'        => 'finance',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
