<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pembayaran</h2>
                <p class="text-sm text-gray-500">Kelola transaksi pembayaran SPPT dan petugas yang memproses.</p>
            </div>
            @if (auth()->user()->role === 'admin')
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pembayaran.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700">Tambah
                        Pembayaran</a>
                    <a href="{{ route('pembayaran.collective') }}"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg shadow-sm hover:bg-emerald-700">Pembayaran
                        Kolektif</a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @if (session('success'))
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3">
                        {{ session('success') }}</div>
                @endif

                <div class="space-y-4">
                    <form action="{{ route('pembayaran.index') }}" method="GET"
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <label class="flex-1">
                            <span class="sr-only">Cari</span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari ID bayar, SPPT, tanggal, atau petugas"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none" />
                        </label>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-slate-800 text-white rounded-lg shadow-sm hover:bg-slate-900">Cari</button>
                            <a href="{{ route('pembayaran.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-slate-700 rounded-lg shadow-sm hover:bg-gray-200">Reset</a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), ['sort' => 'id_bayar', 'direction' => request('sort') === 'id_bayar' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            ID Bayar
                                            @if (request('sort') === 'id_bayar')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), ['sort' => 'id_sppt', 'direction' => request('sort') === 'id_sppt' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            SPPT
                                            @if (request('sort') === 'id_sppt')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), ['sort' => 'tgl_bayar', 'direction' => request('sort') === 'tgl_bayar' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Tanggal Bayar
                                            @if (request('sort') === 'tgl_bayar')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), ['sort' => 'jumlah_bayar', 'direction' => request('sort') === 'jumlah_bayar' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Jumlah
                                            @if (request('sort') === 'jumlah_bayar')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), ['sort' => 'id_petugas', 'direction' => request('sort') === 'id_petugas' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Petugas
                                            @if (request('sort') === 'id_petugas')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pembayaran as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->id_bayar }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->sppt?->id_sppt ?? $item->id_sppt }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->tgl_bayar->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ number_format($item->jumlah_bayar, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->petugas?->username ?? $item->id_petugas }}</td>
                                        <td class="px-4 py-3 text-sm text-right space-x-2">
                                            @if (auth()->user()->role === 'admin')
                                                <a href="{{ route('pembayaran.edit', $item->id_bayar) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('pembayaran.destroy', $item->id_bayar) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Hapus pembayaran ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada
                                            transaksi pembayaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-4">{{ $pembayaran->links() }}</div>
                </div>
            </div>
        </div>
</x-app-layout>
