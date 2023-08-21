<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
//            [
//                'name' => 'admin',
//                'guard_name' => 'operator'
//            ],
//            [
//                'name' => 'user_managing',
//                'guard_name' => 'operator'
//            ],
//            [
//                'name' => 'movie_managing',
//                'guard_name' => 'operator'
//            ],
            [
                'name' => 'admin',
                'guard_name' => 'user'
            ],
            [
                'name' => 'user_managing',
                'guard_name' => 'user'
            ],
            [
                'name' => 'movie_managing',
                'guard_name' => 'user'
            ],
            [
                'name' => 'user',
                'guard_name' => 'user'
            ],
        ]);
    }
}
