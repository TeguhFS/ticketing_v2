<x-app-layout>
    <x-slot name="title">Export Orders</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">

            <div class="w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="ti ti-file-spreadsheet text-white text-2xl"></i>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Export Data Order</h2>
            <p class="text-sm text-gray-400 mb-8">
                Download semua data order dalam format Excel. Anda bisa filter berdasarkan status dan tanggal sebelum
                export.
            </p>

            <form method="GET" action="{{ route('admin.orders.export') }}" class="text-left space-y-4 mb-6">

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Filter Status</label>
                    <select name="status"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="expired">Expired</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

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

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-file-spreadsheet text-base"></i>
                    Download Excel
                </button>

            </form>

            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-400 hover:text-gray-700 transition">
                ← Kembali ke Orders
            </a>

        </div>
    </div>

</x-app-layout>
