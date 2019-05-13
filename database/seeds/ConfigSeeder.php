<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'site_name' => 'Example Blog',
            'site_keywords' => 'Blog, PHP',
            'site_description' => 'Example Blog',
            'site_copyright' => 'Copyright © 2019',
            'site_backend_2fa' => '0',
            'site_bing_auth' => '',
            'site_google_verification' => '',
            'site_google_analytics_code' => '',
        ];

        foreach ($data as $key => $val) {
            factory(App\Models\Config::class)->create([
                'config_name' => $key,
                'config_value' => $val,
            ]);
        }
    }
}
