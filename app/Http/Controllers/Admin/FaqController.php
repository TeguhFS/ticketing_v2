<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->get();

        $stats = [
            'total'    => Faq::count(),
            'active'   => Faq::where('is_active', true)->count(),
            'inactive' => Faq::where('is_active', false)->count(),
        ];

        return view('admin.faqs.index', compact('faqs', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Faq::create([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? Faq::max('order') + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string',
            'order'     => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $faq->update([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? $faq->order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders'   => 'required|array',
            'orders.*' => 'integer|exists:faqs,id',
        ]);

        foreach ($request->orders as $order => $id) {
            Faq::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
