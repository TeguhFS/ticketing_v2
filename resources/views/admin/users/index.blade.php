<x-app-layout>
    <div x-data="{
        deleteModal: false,
        deleteAction: '',
        deleteTitle: '',
        deleteMessage: ''
    }">
        <x-slot name="title">Users</x-slot>

        @if (session('success'))
            <div
                class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
                <i class="ti ti-circle-x text-base"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
            @foreach ([['label' => 'Total Users', 'value' => $stats['total'], 'icon' => 'ti-users', 'color' => 'bg-gray-900'], ['label' => 'User', 'value' => $stats['user'], 'icon' => 'ti-user', 'color' => 'bg-blue-500'], ['label' => 'Admin', 'value' => $stats['admin'], 'icon' => 'ti-shield', 'color' => 'bg-violet-500'], ['label' => 'Field Officer', 'value' => $stats['field_officer'], 'icon' => 'ti-id-badge', 'color' => 'bg-amber-500'], ['label' => 'Nonaktif', 'value' => $stats['inactive'], 'icon' => 'ti-user-off', 'color' => 'bg-red-400']] as $stat)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                        <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                            <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Daftar Users</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola semua akun pengguna</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    <i class="ti ti-plus text-base"></i> Tambah User
                </a>
            </div>

            {{-- Filter --}}
            <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                <form method="GET" class="flex flex-wrap gap-3 items-center">

                    {{-- Input Search --}}
                    <div
                        class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                        <i class="ti ti-search text-gray-300 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email, nomor hp..."
                            class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                    </div>

                    {{-- Select Role --}}
                    <div class="relative flex items-center min-w-[150px]">
                        <select name="role"
                            class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                            <option value="">Semua Role</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="field_officer" {{ request('role') === 'field_officer' ? 'selected' : '' }}>
                                Field
                                Officer</option>
                        </select>
                        <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                            <i class="ti ti-chevron-down text-xs"></i>
                        </div>
                    </div>

                    {{-- Select Status --}}
                    <div class="relative flex items-center min-w-[140px]">
                        <select name="status"
                            class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                        <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                            <i class="ti ti-chevron-down text-xs"></i>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <button type="submit"
                        class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                        Filter
                    </button>

                    @if (request()->anyFilled(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}"
                            class="h-9 px-4 flex items-center justify-center border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">User</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Role</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">No. HP</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Orders</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tiket</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Bergabung</th>
                            <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if ($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}"
                                                class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $roleCfg = [
                                            'admin' => 'bg-violet-50 text-violet-700 border-violet-200',
                                            'user' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            'field_officer' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        ];
                                    @endphp
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $roleCfg[$user->role] ?? '' }} capitalize">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600">
                                    {{ $user->phone ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600">
                                    {{ $user->orders_count }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600">
                                    {{ $user->tickets_count }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            {{ $user->is_active
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                            <i class="ti ti-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center transition
                                        {{ $user->is_active
                                            ? 'text-gray-400 hover:bg-amber-50 hover:text-amber-500 hover:border-amber-200'
                                            : 'text-gray-400 hover:bg-emerald-50 hover:text-emerald-500 hover:border-emerald-200' }}"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i
                                                    class="ti {{ $user->is_active ? 'ti-user-off' : 'ti-user-check' }} text-sm"></i>
                                            </button>
                                        </form>
                                        @if ($user->id !== auth()->id())
                                            <button type="button"
                                                @click="
        deleteModal = true;
        deleteAction = '{{ route('admin.users.destroy', $user) }}';
        deleteTitle = 'Hapus User';
        deleteMessage = 'Yakin ingin menghapus user {{ addslashes($user->name) }}?';
    "
                                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <i class="ti ti-users-off text-4xl text-gray-200 block mb-3"></i>
                                    <p class="text-sm text-gray-400">Belum ada user terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif

        </div>

        {{-- Delete Modal --}}
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display:none">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50" @click="deleteModal = false"></div>

            {{-- Modal --}}
            <div x-show="deleteModal" x-transition
                class="relative bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                            <i class="ti ti-trash"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900" x-text="deleteTitle"></h3>
                            <p class="text-sm text-gray-500" x-text="deleteMessage"></p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button @click="deleteModal = false"
                            class="h-9 px-4 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                            Batal
                        </button>

                        <form :action="deleteAction" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="h-9 px-4 bg-red-600 text-white rounded-xl text-sm hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
