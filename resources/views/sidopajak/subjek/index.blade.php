<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subjek Pajak</h2>
                <p class="text-sm text-gray-500">Kelola identitas wajib pajak, import, export, dan cetak PDF.</p>
            </div>
            @if (auth()->user()->role === 'admin')
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('subjek-pajak.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700">Tambah
                        Subjek</a>
                    <a href="{{ route('subjek-pajak.export') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow-sm hover:bg-green-700">Export
                        Excel</a>
                    <a href="{{ route('subjek-pajak.pdf') }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg shadow-sm hover:bg-slate-700">Export
                        PDF</a>
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
                @if (session('error'))
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                        {{ session('error') }}</div>
                @endif

                <div class="space-y-4">
                    <form action="{{ route('subjek-pajak.index') }}" method="GET"
                        class="grid gap-3 sm:grid-cols-3 sm:items-end">
                        <label class="sm:col-span-2">
                            <span class="sr-only">Cari</span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari NIK, nama, alamat, atau no HP"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none" />
                        </label>
                        <label>
                            <span class="sr-only">RT</span>
                            <input type="text" name="rt" value="{{ request('rt') }}" placeholder="RT"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none" />
                        </label>
                        <label>
                            <span class="sr-only">RW</span>
                            <input type="text" name="rw" value="{{ request('rw') }}" placeholder="RW"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none" />
                        </label>
                        <div class="flex flex-wrap gap-2 sm:col-span-3">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-slate-800 text-white rounded-lg shadow-sm hover:bg-slate-900">Cari</button>
                            <a href="{{ route('subjek-pajak.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-slate-700 rounded-lg shadow-sm hover:bg-gray-200">Reset</a>
                        </div>
                    </form>

                    @if (auth()->user()->role === 'admin')
                        <div class="upload-form-wrapper space-y-3">
                            <form action="{{ route('subjek-pajak.import') }}" method="POST"
                                enctype="multipart/form-data"
                                class="upload-progress-form flex flex-col sm:flex-row sm:items-center gap-3">
                                @csrf
                                <label
                                    class="w-full sm:w-auto flex items-center gap-3 bg-slate-100 rounded-lg px-4 py-3">
                                    <span class="text-sm text-slate-600">Import Excel</span>
                                    <input type="file" name="file" accept=".xlsx,.xls,.csv"
                                        class="text-sm text-slate-500" />
                                </label>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700">Unggah</button>
                            </form>

                            <div class="upload-progress hidden rounded-lg bg-slate-100 p-4">
                                <div class="h-2 rounded-full bg-slate-200 overflow-hidden">
                                    <div class="upload-progress-bar h-2 bg-indigo-600 w-0"></div>
                                </div>
                                <p class="upload-status mt-2 text-sm text-slate-600">Mengunggah file...</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <a href="{{ route('subjek-pajak.index', array_merge(request()->except('page'), ['sort' => 'nik', 'direction' => request('sort') === 'nik' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="inline-flex items-center gap-1">
                                        NIK
                                        @if (request('sort') === 'nik')
                                            <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <a href="{{ route('subjek-pajak.index', array_merge(request()->except('page'), ['sort' => 'nama', 'direction' => request('sort') === 'nama' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="inline-flex items-center gap-1">
                                        Nama
                                        @if (request('sort') === 'nama')
                                            <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <a href="{{ route('subjek-pajak.index', array_merge(request()->except('page'), ['sort' => 'alamat', 'direction' => request('sort') === 'alamat' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="inline-flex items-center gap-1">
                                        Alamat
                                        @if (request('sort') === 'alamat')
                                            <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <a href="{{ route('subjek-pajak.index', array_merge(request()->except('page'), ['sort' => 'rw', 'direction' => request('sort') === 'rw' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="inline-flex items-center gap-1">
                                        RT/RW
                                        @if (request('sort') === 'rw')
                                            <span>{{ request('direction') === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <a href="{{ route('subjek-pajak.index', array_merge(request()->except('page'), ['sort' => 'no_hp', 'direction' => request('sort') === 'no_hp' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="inline-flex items-center gap-1">
                                        No HP
                                        @if (request('sort') === 'no_hp')
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
                            @forelse ($subjek as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->nik }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->nama }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->alamat }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $item->rt }}/{{ $item->rw }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->no_hp }}</td>
                                    <td class="px-4 py-3 text-sm text-right space-x-2">
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('subjek-pajak.edit', $item->nik) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('subjek-pajak.destroy', $item->nik) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Hapus data ini?');">
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
                                        data subjek pajak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4">{{ $subjek->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
