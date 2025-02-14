<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user= User::create(['email' => 'admin@admin.com','name'=>"Admin",'password' => Hash::make('password'),"is_admin"=>true]);
       $user->assignRole('admin');
    }
}
