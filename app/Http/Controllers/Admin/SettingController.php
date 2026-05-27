<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name'        => 'required|string|max:100',
            'app_description' => 'nullable|string|max:500',
            'app_email'       => 'nullable|email',
            'app_phone'       => 'nullable|string|max:20',
            'app_address'     => 'nullable|string|max:255',
            'app_logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'app_favicon'     => 'nullable|image|mimes:jpg,jpeg,png,ico|max:512',
        ]);

        $data = $request->only([
            'app_name',
            'app_description',
            'app_email',
            'app_phone',
            'app_address',
        ]);

        // Upload logo
        if ($request->hasFile('app_logo')) {
            $old = Setting::get('app_logo');
            if ($old) Storage::disk('public')->delete($old);
            $data['app_logo'] = $request->file('app_logo')->store('settings', 'public');
        }

        // Upload favicon
        if ($request->hasFile('app_favicon')) {
            $old = Setting::get('app_favicon');
            if ($old) Storage::disk('public')->delete($old);
            $data['app_favicon'] = $request->file('app_favicon')->store('settings', 'public');
        }

        Setting::setMany($data, 'general');

        return back()->with('success', 'Pengaturan umum berhasil disimpan!');
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'social_instagram' => 'nullable|url',
            'social_twitter'   => 'nullable|url',
            'social_facebook'  => 'nullable|url',
            'social_youtube'   => 'nullable|url',
            'social_tiktok'    => 'nullable|url',
            'social_whatsapp'  => 'nullable|string|max:20',
        ]);

        Setting::setMany($request->only([
            'social_instagram',
            'social_twitter',
            'social_facebook',
            'social_youtube',
            'social_tiktok',
            'social_whatsapp',
        ]), 'social');

        return back()->with('success', 'Pengaturan sosial media berhasil disimpan!');
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'seo_title'       => 'nullable|string|max:70',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords'    => 'nullable|string|max:255',
            'seo_og_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'seo_title',
            'seo_description',
            'seo_keywords',
        ]);

        // Upload OG Image
        if ($request->hasFile('seo_og_image')) {
            $old = Setting::get('seo_og_image');
            if ($old) Storage::disk('public')->delete($old);
            $data['seo_og_image'] = $request->file('seo_og_image')->store('settings', 'public');
        }

        Setting::setMany($data, 'seo');

        return back()->with('success', 'Pengaturan SEO berhasil disimpan!');
    }
}
