<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'amir',
            'password' => Hash::make('12345678'),
            'email' => 'amirghasempoor79@gmail.com',
        ]);

        $role = Role::where('name', 'admin')->first();

        $user->assignRole($role);
    }
}
