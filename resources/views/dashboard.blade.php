<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard SidoPajak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Subjek Pajak</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['subjek'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Jumlah subjek pajak terdaftar</p>
                    <a href="{{ route('subjek-pajak.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat
                        subjek</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Objek Pajak</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['objek'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Jumlah objek pajak tercatat</p>
                    <a href="{{ route('objek-pajak.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat
                        objek</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">SPPT</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['sppt'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Jumlah SPPT yang terdaftar</p>
                    <a href="{{ route('sppt.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat
                        SPPT</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Mutasi</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['mutasi'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Jumlah mutasi tercatat</p>
                    <a href="{{ route('mutasi.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat
                        mutasi</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Pembayaran</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['pembayaran'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">Jumlah pembayaran tercatat</p>
                    <a href="{{ route('pembayaran.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat
                        pembayaran</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">SPPT Piutang</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $summary['unpaid_sppt'] }}</p>
                    <p class="mt-2 text-sm text-gray-600">SPPT belum lunas</p>
                    <a href="{{ route('sppt.index') }}"
                        class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat status
                        bayar</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pembayaran</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">Rp
                        {{ number_format($summary['total_pembayaran'], 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-gray-600">Total pembayaran masuk</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pajak Terhutang</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">Rp
                        {{ number_format($summary['total_terhutang'], 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-gray-600">Total kewajiban pajak yang belum dibayar</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan SPPT per Tahun</h3>
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Tahun</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">
                                        Total SPPT</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">
                                        Lunas</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">
                                        Piutang</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">
                                        Total Terhutang</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($spptByYear as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">{{ $row->tahun }}</td>
                                        <td class="px-4 py-3 text-right text-gray-900">{{ $row->total }}</td>
                                        <td class="px-4 py-3 text-right text-green-600">{{ $row->paid }}</td>
                                        <td class="px-4 py-3 text-right text-red-600">{{ $row->unpaid }}</td>
                                        <td class="px-4 py-3 text-right text-gray-900">Rp
                                            {{ number_format($row->total_terhutang, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-3 text-gray-500" colspan="5">Belum ada data SPPT
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('subjek-pajak.create') }}"
                            class="block rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Tambah
                            Subjek Pajak</a>
                        <a href="{{ route('objek-pajak.create') }}"
                            class="block rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Tambah
                            Objek Pajak</a>
                        <a href="{{ route('sppt.create') }}"
                            class="block rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Tambah
                            SPPT</a>
                        <a href="{{ route('mutasi.create') }}"
                            class="block rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Tambah
                            Mutasi</a>
                        <a href="{{ route('pembayaran.create') }}"
                            class="block rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Tambah
                            Pembayaran</a>
                    </div>
                </div>

                <div class="grid gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Subjek Pajak Terbaru</h3>
                        <div class="mt-4 space-y-3">
                            @forelse($latestSubjects as $subjek)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <p class="text-base font-semibold text-gray-900">{{ $subjek->nama }}</p>
                                    <p class="text-sm text-gray-500">NIK: {{ $subjek->nik }}</p>
                                    <p class="mt-1 text-sm text-gray-600">{{ $subjek->alamat }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada data subjek pajak.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pembayaran Terbaru</h3>
                        <div class="mt-4 space-y-3">
                            @forelse($latestPayments as $payment)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <p class="text-base font-semibold text-gray-900">
                                        {{ $payment->sppt?->nop ?? 'SPPT tidak tersedia' }}</p>
                                    <p class="text-sm text-gray-500">Tanggal:
                                        {{ $payment->tgl_bayar->format('d M Y') }}</p>
                                    <p class="mt-1 text-sm text-gray-600">Jumlah: Rp
                                        {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada pembayaran.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
