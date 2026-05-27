<x-app-layout>
    <x-slot name="title">Export Transaksi</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">

            <div class="w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="ti ti-file-spreadsheet text-white text-2xl"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Export Laporan Transaksi</h2>
            <p class="text-sm text-gray-400 mb-8">
                Download laporan transaksi terverifikasi dalam format Excel (.xlsx).
            </p>

            <form method="GET" action="{{ route('admin.transactions.export') }}" class="text-left space-y-4 mb-6">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Dari Tanggal</label>
                        <input type="date" name="date_from"
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="date_to"
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Filter Metode Pembayaran</label>
                    <select name="payment_method_id"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                        <option value="">Semua Metode</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="download" value="1">

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-file-spreadsheet text-base"></i>
                    Download Excel
                </button>

            </form>

            <a href="{{ route('admin.transactions.index') }}"
                class="text-sm text-gray-400 hover:text-gray-700 transition">
                ← Kembali ke Transaksi
            </a>

        </div>
    </div>

</x-app-layout>
