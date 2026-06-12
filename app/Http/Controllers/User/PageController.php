<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function privacy()
    {
        $page = Page::where('type', 'privacy')
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.pages.show', compact('page'));
    }

    public function terms()
    {
        $page = Page::where('type', 'terms')
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.pages.show', compact('page'));
    }

    public function show(Page $page)
    {
        abort_if(!$page->is_active, 404);
        return view('user.pages.show', compact('page'));
    }
}
