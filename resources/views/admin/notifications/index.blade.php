<x-app-layout>
    <x-slot name="title">Notifikasi</x-slot>

    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Semua Notifikasi</h1>
                <p class="text-xs text-gray-400 mt-1">
                    Riwayat semua aktivitas yang masuk
                </p>
            </div>
            @if ($notifications->total() > 0)
                <form id="delete-all-form" action="{{ route('admin.notifications.destroyAll') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" onclick="confirmDeleteAll()"
                        class="inline-flex items-center gap-2 h-9 px-4 border border-red-200
        text-red-500 text-sm rounded-xl hover:bg-red-50 transition">
                        <i class="ti ti-trash text-base"></i>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <div class="text-center py-20 bg-white border border-gray-100 rounded-2xl">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-bell-off text-gray-400 text-3xl"></i>
                </div>
                <p class="text-base font-semibold text-gray-600 mb-1">Tidak ada notifikasi</p>
                <p class="text-sm text-gray-400">Semua aktivitas akan muncul di sini</p>
            </div>
        @else
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach ($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp

                        <div
                            class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50/50
                transition-colors duration-150
                {{ $isUnread ? 'bg-blue-50/30' : '' }}">

                            {{-- Icon --}}
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5
                    {{ $data['bg'] ?? 'bg-gray-100' }}">
                                <i
                                    class="ti {{ $data['icon'] ?? 'ti-bell' }} text-base
                        {{ $data['color'] ?? 'text-gray-500' }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $data['title'] ?? '' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">
                                            {{ $data['message'] ?? '' }}
                                        </p>
                                    </div>
                                    @if ($isUnread)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 mt-2">
                                    <p class="text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                        · {{ $notification->created_at->format('d M Y, H:i') }}
                                    </p>
                                    @if (!empty($data['amount']))
                                        <span class="text-xs font-semibold text-gray-600">
                                            Rp {{ number_format($data['amount'], 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-3 mt-2">
                                    @if (!empty($data['url']) && $data['url'] !== '#')
                                        <a href="{{ $data['url'] }}"
                                            class="text-xs font-medium text-blue-600 hover:text-blue-800
                                transition flex items-center gap-1">
                                            Lihat Detail
                                            <i class="ti ti-arrow-right text-xs"></i>
                                        </a>
                                    @endif

                                    <form id="delete-notification-{{ $notification->id }}"
                                        action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            onclick="confirmDeleteNotification('{{ $notification->id }}')"
                                            class="text-xs text-gray-400 hover:text-red-500 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

        @endif

    </div>

    @push('scripts')
        <script>
            function confirmDeleteAll() {
                Swal.fire({
                    title: 'Hapus semua notifikasi?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-all-form').submit();
                    }
                });
            }

            function confirmDeleteNotification(id) {
                Swal.fire({
                    title: 'Hapus notifikasi?',
                    text: 'Notifikasi akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-notification-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
