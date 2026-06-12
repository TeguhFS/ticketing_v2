<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $sections = AboutSection::orderBy('order')->get();
        return view('admin.about.index', compact('sections'));
    }

    public function update(Request $request, AboutSection $about)
    {
        $request->validate([
            'title'     => 'nullable|string|max:255',
            'subtitle'  => 'nullable|string|max:500',
            'content'   => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = $about->image;
        if ($request->hasFile('image')) {
            if ($about->image) Storage::disk('public')->delete($about->image);
            $imagePath = $request->file('image')->store('about', 'public');
        }

        // Handle delete image
        if ($request->boolean('delete_image') && $about->image) {
            Storage::disk('public')->delete($about->image);
            $imagePath = null;
        }

        $about->update([
            'title'     => $request->title,
            'subtitle'  => $request->subtitle,
            'content'   => $request->content,
            'image'     => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Clear cache
        AboutSection::clearCache($about->key);

        return back()->with('success', 'Section "' . $about->title . '" berhasil diperbarui!');
    }

    public function updateItems(Request $request, AboutSection $about)
    {
        $request->validate([
            'items'   => 'nullable|array',
            'items.*' => 'array',
            'items.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $itemsData = $request->items ?? [];
        $processedItems = [];

        foreach ($itemsData as $index => $item) {
            $imagePath = $item['old_image'] ?? null;

            if ($request->hasFile("items.{$index}.image")) {
                if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file("items.{$index}.image")->store('about/team', 'public');
            }

            unset($item['old_image']);

            $item['image'] = $imagePath;

            $processedItems[] = $item;
        }

        $about->update(['items' => $processedItems]);

        AboutSection::clearCache($about->key);

        return back()->with('success', 'Items berhasil diperbarui!');
    }

    public function toggleActive(AboutSection $about)
    {
        $about->update(['is_active' => !$about->is_active]);
        AboutSection::clearCache($about->key);

        return back()->with('success', 'Status section berhasil diperbarui!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders'   => 'required|array',
            'orders.*' => 'integer|exists:about_sections,id',
        ]);

        foreach ($request->orders as $order => $id) {
            AboutSection::where('id', $id)->update(['order' => $order]);
            $section = AboutSection::find($id);
            if ($section) AboutSection::clearCache($section->key);
        }

        return response()->json(['success' => true]);
    }
}
