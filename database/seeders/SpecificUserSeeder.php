<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SpecificUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Minella',
                'email' => 'minella@email.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ],
             [
                'name' => 'Akhsan',
                'email' => 'akhsan@email.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Mas Ryan',
                'email' => 'ryan@email.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Ima',
                'email' => 'ima@email.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ],
            [
                'name' => 'Kisno',
                'email' => 'kisno@email.com',
                'password' => Hash::make('password'),
                'role' => 'gudang',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            // Assign role using Spatie Permission (consistent with DatabaseSeeder)
            $user->assignRole($userData['role']);
        }
    }
}
