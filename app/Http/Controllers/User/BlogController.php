<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('author')
            ->where('status', 'published')
            ->orderByDesc('published_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $blogs = $query->paginate(9)->withQueryString();

        $featuredBlogs = Blog::with('author')
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $latestBlogs = Blog::with('author')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        $totalBlogs = Blog::where('status', 'published')->count();

        return view('user.blogs.index', compact(
            'blogs',
            'featuredBlogs',
            'latestBlogs',
            'totalBlogs',
        ));
    }

    public function show(Blog $blog)
    {
        abort_if($blog->status !== 'published', 404);

        $blog->load('author');

        $relatedBlogs = Blog::with('author')
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $recentBlogs = Blog::with('author')
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('user.blogs.show', compact('blog', 'relatedBlogs', 'recentBlogs'));
    }
}
