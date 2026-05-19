<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Mutasi</h2>
                <p class="text-sm text-gray-500">Rekam perpindahan kepemilikan tanah dan audit trail NOP.</p>
            </div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('mutasi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700">Tambah
                    Mutasi</a>
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
                    <form action="{{ route('mutasi.index') }}" method="GET"
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <label class="flex-1">
                            <span class="sr-only">Cari</span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari ID, NOP, nik lama/baru, atau jenis mutasi"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none" />
                        </label>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-slate-800 text-white rounded-lg shadow-sm hover:bg-slate-900">Cari</button>
                            <a href="{{ route('mutasi.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-slate-700 rounded-lg shadow-sm hover:bg-gray-200">Reset</a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'id_mutasi', 'direction' => request('sort') === 'id_mutasi' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            ID Mutasi
                                            @if (request('sort') === 'id_mutasi')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'nop_asal', 'direction' => request('sort') === 'nop_asal' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            NOP Asal
                                            @if (request('sort') === 'nop_asal')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'nik_lama', 'direction' => request('sort') === 'nik_lama' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Pemilik Lama
                                            @if (request('sort') === 'nik_lama')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'nik_baru', 'direction' => request('sort') === 'nik_baru' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Pemilik Baru
                                            @if (request('sort') === 'nik_baru')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'jenis_mutasi', 'direction' => request('sort') === 'jenis_mutasi' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Jenis
                                            @if (request('sort') === 'jenis_mutasi')
                                                <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <a href="{{ route('mutasi.index', array_merge(request()->except('page'), ['sort' => 'tgl_mutasi', 'direction' => request('sort') === 'tgl_mutasi' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="inline-flex items-center gap-1">
                                            Tanggal
                                            @if (request('sort') === 'tgl_mutasi')
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
                                @forelse ($mutasi as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->id_mutasi }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->nop_asal }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->subjekLama?->nama ?? $item->nik_lama }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->subjekBaru?->nama ?? $item->nik_baru }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($item->jenis_mutasi) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $item->tgl_mutasi->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right space-x-2">
                                            @if (auth()->user()->role === 'admin')
                                                <a href="{{ route('mutasi.edit', $item->id_mutasi) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('mutasi.destroy', $item->id_mutasi) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Hapus riwayat mutasi ini?');">
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
                                        <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada
                                            riwayat mutasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-4">{{ $mutasi->links() }}</div>
                </div>
            </div>
        </div>
</x-app-layout>
