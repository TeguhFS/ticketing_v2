<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('author')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('excerpt', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $blogs = $query->paginate(12)->withQueryString();

        $stats = [
            'total'     => Blog::count(),
            'published' => Blog::where('status', 'published')->count(),
            'draft'     => Blog::where('status', 'draft')->count(),
            'featured'  => Blog::where('is_featured', true)->count(),
        ];

        return view('admin.blogs.index', compact('blogs', 'stats'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:500',
            'content'     => 'required|string',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('blogs', 'public');
        }

        Blog::create([
            'user_id'      => Auth::id(),
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(5),
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'],
            'thumbnail'    => $thumbnailPath,
            'status'       => $validated['status'],
            'is_featured'  => $request->boolean('is_featured'),
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil dipublikasikan!');
    }

    public function show(Blog $blog)
    {
        $blog->load('author');
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:500',
            'content'     => 'required|string',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ]);

        $thumbnailPath = $blog->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
            $thumbnailPath = $request->file('thumbnail')->store('blogs', 'public');
        }

        $blog->update([
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(5),
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'],
            'thumbnail'    => $thumbnailPath,
            'status'       => $validated['status'],
            'is_featured'  => $request->boolean('is_featured'),
            'published_at' => $validated['status'] === 'published'
                ? ($blog->published_at ?? now())
                : null,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil diperbarui!');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil dihapus.');
    }
}
