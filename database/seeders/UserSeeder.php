<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' =>  Hash::make('admin1212'),
            'role' => 'admin'
        ]);
        DB::table('users')->insert([
            'name' => 'ceo',
            'email' => 'ceo@gmail.com',
            'password' =>  Hash::make('ceo1212'),
            'role' => 'ceo'
        ]);
        DB::table('users')->insert([
            'name' => 'kurir',
            'email' => 'kurir@gmail.com',
            'password' =>  Hash::make('kurir1212'),
            'role' => 'kurir'
        ]);
        DB::table('users')->insert([
            'name' => 'marketing',
            'email' => 'marketing@gmail.com',
            'password' =>  Hash::make('marketing1212'),
            'role' => 'marketing'
        ]);
    }
}
