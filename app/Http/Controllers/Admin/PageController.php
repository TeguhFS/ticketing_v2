<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();

        $stats = [
            'total'    => Page::count(),
            'active'   => Page::where('is_active', true)->count(),
            'privacy'  => Page::where('type', 'privacy')->count(),
            'terms'    => Page::where('type', 'terms')->count(),
            'custom'   => Page::where('type', 'custom')->count(),
        ];

        return view('admin.pages.index', compact('pages', 'stats'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:pages,slug',
            'content'      => 'required|string',
            'type'         => 'required|in:privacy,terms,custom',
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Cek jika type privacy/terms sudah ada
        if (in_array($validated['type'], ['privacy', 'terms'])) {
            $exists = Page::where('type', $validated['type'])->exists();
            if ($exists) {
                return back()->withErrors([
                    'type' => 'Halaman ' . ucfirst($validated['type']) . ' sudah ada. Silakan edit yang sudah ada.'
                ])->withInput();
            }
        }

        Page::create([
            'title'        => $validated['title'],
            'slug'         => $validated['slug'] ?? Str::slug($validated['title']),
            'content'      => $validated['content'],
            'type'         => $validated['type'],
            'is_active'    => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dibuat!');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content'      => 'required|string',
            'type'         => 'required|in:privacy,terms,custom',
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $page->update([
            'title'        => $validated['title'],
            'slug'         => $validated['slug'] ?? Str::slug($validated['title']),
            'content'      => $validated['content'],
            'type'         => $validated['type'],
            'is_active'    => $request->boolean('is_active'),
            'published_at' => $validated['published_at'] ?? $page->published_at,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui!');
    }

    public function destroy(Page $page)
    {
        // Protect privacy & terms dari penghapusan
        if (in_array($page->type, ['privacy', 'terms'])) {
            return back()->with('error', 'Halaman ' . $page->title . ' tidak dapat dihapus!');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}
