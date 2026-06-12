<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Mark all as read when viewing index
        auth()->user()->unreadNotifications->markAsRead();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get();

        $count = auth()->user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->data['type']    ?? '',
                'icon'       => $n->data['icon']    ?? 'ti-bell',
                'color'      => $n->data['color']   ?? 'text-gray-500',
                'bg'         => $n->data['bg']      ?? 'bg-gray-50',
                'title'      => $n->data['title']   ?? '',
                'message'    => $n->data['message'] ?? '',
                'amount'     => $n->data['amount']  ?? null,
                'url'        => $n->data['url']     ?? '#',
                'created_at' => $n->created_at->diffForHumans(),
                'read_at'    => $n->read_at,
            ]),
            'count'         => $count,
            'has_more'      => auth()->user()->notifications()->count() > 10,
        ]);
    }

    public function markAsRead(Request $request)
    {
        if ($request->id) {
            auth()->user()
                ->notifications()
                ->where('id', $request->id)
                ->update(['read_at' => now()]);
        } else {
            auth()->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll()
    {
        auth()->user()
            ->notifications()
            ->delete();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
