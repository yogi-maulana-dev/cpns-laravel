<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('app_settings')->truncate();
        Schema::enableForeignKeyConstraints();

        AppSetting::create([
            'web_name' => 'CAT CPNS',
            'web_description' => 'Website Ujian CAT CPNS',
            'footer' => 'Website Ujian CAT CPNS',
            'logo' => 'app-settings/default.png',
            'logo_icon' => 'app-settings/default.png',
            'login_background' => null,
        ]);
    }
}
