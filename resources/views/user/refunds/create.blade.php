<x-auth-layout>
    <x-slot name="title">Ajukan Refund — {{ setting('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('user.orders.show', $order) }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition mb-6">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Detail Order
        </a>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Ajukan Refund</h1>
            <p class="text-sm text-gray-400 mt-1">
                Isi formulir di bawah untuk mengajukan pengembalian dana
            </p>
        </div>

        {{-- Warning --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="ti ti-alert-triangle text-amber-500 text-base flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-amber-800 mb-1">Perhatikan Sebelum Mengajukan</p>
                    <ul class="space-y-1 text-xs text-amber-700">
                        <li>· Refund hanya dapat diajukan sebelum event dimulai</li>
                        <li>· Proses refund membutuhkan 3-5 hari kerja</li>
                        <li>· Tiket akan dibatalkan setelah refund diapprove</li>
                        <li>· Dana akan ditransfer ke rekening yang Anda cantumkan</li>
                        <li>· Pastikan informasi rekening bank yang dimasukkan benar</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('user.refunds.store', $order) }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Reason --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Alasan Refund</h3>
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Jelaskan alasan pengajuan refund <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reason" rows="5"
                                placeholder="Contoh: Saya tidak dapat hadir karena ada keperluan mendadak yang tidak bisa ditinggalkan..."
                                class="w-full px-3 py-2.5 border rounded-xl text-sm text-gray-700
                                outline-none focus:border-gray-300 bg-white resize-none
                                @error('reason') @else border-gray-100 @enderror">{{ old('reason') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Minimal 20 karakter</p>
                            @error('reason')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bank Info --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Informasi Rekening</h3>
                        <p class="text-xs text-gray-400 mb-4">
                            Dana refund akan ditransfer ke rekening berikut setelah diapprove
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Nama Bank <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="ti ti-building-bank absolute left-3 top-1/2 -translate-y-1/2
                                    text-gray-300 text-sm"></i>
                                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                        placeholder="BCA, Mandiri, BNI, BRI..."
                                        class="w-full h-11 pl-9 pr-4 border rounded-xl text-sm
                                        text-gray-700 outline-none bg-white transition-all duration-200
                                        @error('bank_name') @else border-gray-100 focus:border-gray-300 @enderror">
                                </div>
                                @error('bank_name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Nomor Rekening <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="ti ti-credit-card absolute left-3 top-1/2 -translate-y-1/2
                                    text-gray-300 text-sm"></i>
                                    <input type="text" name="account_number" value="{{ old('account_number') }}"
                                        placeholder="Masukkan nomor rekening"
                                        class="w-full h-11 pl-9 pr-4 border rounded-xl text-sm
                                        text-gray-700 outline-none bg-white font-mono
                                        transition-all duration-200
                                        @error('account_number') @else border-gray-100 focus:border-gray-300 @enderror">
                                </div>
                                @error('account_number')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Nama Pemilik Rekening <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="ti ti-user absolute left-3 top-1/2 -translate-y-1/2
                                    text-gray-300 text-sm"></i>
                                    <input type="text" name="account_name"
                                        value="{{ old('account_name', auth()->user()->name) }}"
                                        placeholder="Nama sesuai buku tabungan"
                                        class="w-full h-11 pl-9 pr-4 border rounded-xl text-sm
                                        text-gray-700 outline-none bg-white transition-all duration-200
                                        @error('account_name') @else border-gray-100 focus:border-gray-300 @enderror">
                                </div>
                                @error('account_name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Confirm & Submit --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-5" x-data="{ agreed: false }">
                        <label class="flex items-start gap-3 cursor-pointer mb-5">
                            <div class="relative mt-0.5 flex-shrink-0">
                                <input type="checkbox" x-model="agreed" class="sr-only">
                                <div class="w-5 h-5 rounded-md border-2 flex items-center
                                justify-center transition-all duration-200 cursor-pointer"
                                    :class="agreed ? 'bg-gray-900 border-gray-900' : 'border-gray-300 bg-white'"
                                    @click="agreed = !agreed">
                                    <i class="ti ti-check text-white text-xs" x-show="agreed" x-transition></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Saya menyetujui bahwa pengajuan refund ini akan membatalkan semua tiket
                                terkait order ini. Tindakan ini tidak dapat dibatalkan setelah diapprove.
                            </p>
                        </label>

                        <button type="submit" :disabled="!agreed"
                            :class="agreed
                                ?
                                'bg-gray-900 hover:bg-gray-700 cursor-pointer' :
                                'bg-gray-300 cursor-not-allowed'"
                            class="w-full h-12 text-white font-semibold text-sm rounded-xl
                            transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ti ti-send text-base"></i>
                            Ajukan Refund
                        </button>
                    </div>

                </form>
            </div>

            {{-- Summary --}}
            <div class="space-y-4">

                {{-- Order Summary --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Ringkasan Order</p>
                    <div class="space-y-3 mb-4">
                        @foreach ($order->orderItems as $item)
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                    @if ($item->ticketType->event->thumbnail)
                                        <img src="{{ Storage::url($item->ticketType->event->thumbnail) }}"
                                            class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="ti ti-calendar-event text-gray-300 text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 line-clamp-2 leading-snug">
                                        {{ $item->ticketType->event->title }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $item->ticketType->name }} × {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 pt-3 space-y-2">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Total Bayar</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-emerald-600">
                            <span>Jumlah Refund</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Proses Refund</p>
                    <div class="space-y-4">
                        @foreach ([['step' => '1', 'title' => 'Ajukan Request', 'desc' => 'Isi formulir & kirim pengajuan', 'done' => true], ['step' => '2', 'title' => 'Review Admin', 'desc' => 'Tim kami review dalam 1-2 hari', 'done' => false], ['step' => '3', 'title' => 'Transfer Dana', 'desc' => 'Dana ditransfer 3-5 hari kerja', 'done' => false]] as $step)
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-7 h-7 rounded-full flex items-center justify-center
                            text-xs font-bold flex-shrink-0
                            {{ $step['done'] ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $step['step'] }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $step['title'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-auth-layout>
