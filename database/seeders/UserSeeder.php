<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        User::factory()->create([
            'name' => 'Muhammad Pauzi',
            'email' => 'muhammadpauzi@gmail.com',
            'role' => UserRole::SUPERADMIN(),
        ]);

        User::factory()->create([
            'name' => 'Operator Ujian',
            'email' => 'operatorujian@gmail.com',
            'role' => UserRole::OPERATOR_UJIAN(),
        ]);

        User::factory()->create([
            'name' => 'Operator Soal',
            'email' => 'operatorsoal@gmail.com',
            'role' => UserRole::OPERATOR_SOAL(),
        ]);

        User::factory(10)->create();
    }
}
