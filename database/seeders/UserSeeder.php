<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = File::get(database_path('json/superAdminCredential.json'));
        $credential = collect(json_decode($jsonData));
        $credential->each(function($value){
            DB::table('users')->insert([
                'name' => $value->name,
                'email' => $value->email, 
                'pass'  => Hash::make($value->pass),
                //'pass'  => $value->pass,
                'type'  =>$value->type,
                'c_id'  => $value->c_id,
                'created_at' => now(),
                'updated_at'  => now(), 
            ]);
        });
    }
}
