<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::where('is_active', true)->orderBy('order');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('question', 'like', '%' . $request->search . '%')
                    ->orWhere('answer', 'like', '%' . $request->search . '%');
            });
        }

        $faqs      = $query->get();
        $totalFaqs = Faq::where('is_active', true)->count();

        return view('user.faqs.index', compact('faqs', 'totalFaqs'));
    }
}
