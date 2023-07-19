<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('departments')->insert([
           [ 'name' => 'HR'],
           [ 'name' => 'TI'],
           [ 'name' => 'QA'],
        ]);

        DB::table('rooms')->insert([
            [ 'name' => 'ROOM_911'],
        ]);

        DB::table('users')->insert([
            'name' => 'Laravel',
            'lastname' => 'ten',
            'email' => 'john.doe@example.com',
            'document' => '0000',
            'code' => '0000',
            'password' => Hash::make('laravel2023'),
        ]);

        DB::table('room_users')->insert([
            [ 'user_id' => 1, 'room_id' => 1],
        ]);
    }
}
