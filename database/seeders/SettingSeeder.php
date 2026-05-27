<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'TicketIn', 'group' => 'general'],
            ['key' => 'app_description', 'value' => 'Platform tiket event terbaik', 'group' => 'general'],
            ['key' => 'app_email', 'value' => 'hello@ticketin.id', 'group' => 'general'],
            ['key' => 'app_phone', 'value' => '+62 812-3456-7890', 'group' => 'general'],
            ['key' => 'app_address', 'value' => 'Jakarta, Indonesia', 'group' => 'general'],
            ['key' => 'app_logo', 'value' => null, 'group' => 'general'],
            ['key' => 'app_favicon', 'value' => null, 'group' => 'general'],

            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/ticketin', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/ticketin', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/ticketin', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@ticketin', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@ticketin', 'group' => 'social'],
            ['key' => 'social_whatsapp', 'value' => 6229182938283, 'group' => 'social'],

            // SEO
            ['key' => 'seo_title', 'value' => 'TicketIn — Beli Tiket Event Online', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Temukan dan beli tiket event terbaik di TicketIn.', 'group' => 'seo'],
            ['key' => 'seo_keywords', 'value' => 'tiket, event, konser, seminar, festival', 'group' => 'seo'],
            ['key' => 'seo_og_image', 'value' => null, 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
