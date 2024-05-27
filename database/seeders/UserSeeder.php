<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin','client','writer','editor'];

        foreach ($roles as $role) {
            if (Role::where('name',$role)->doesntExist()) {
                Role::create(['name' => $role]);
            }
        }

        $admin = User::create([
            'full_name' => 'admin',
            'username' => 'admin682',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole('admin');

        $client = User::create([
            'full_name' => 'Usama Abbasi',
            'username' => 'usama682',
            'email' => 'usamaabbasi682@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $client->assignRole('client');


        $writer = User::create([
            'full_name' => 'writer',
            'username' => 'writer682',
            'email' => 'writer@test.com',
            'password' => Hash::make('12345678'),
        ]);
        $writer->assignRole('writer');

        $editor = User::create([
            'full_name' => 'editor',
            'username' => 'editor682',
            'email' => 'editor@test.com',
            'password' => Hash::make('12345678'),
        ]);
        $editor->assignRole('editor');

    }
}
