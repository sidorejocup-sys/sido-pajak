<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah SPPT</h2>
                <p class="text-sm text-gray-500">Masukkan tagihan SPPT baru untuk objek pajak tertentu.</p>
            </div>
            <a href="{{ route('sppt.index') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('sppt.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID SPPT</label>
                            <input type="text" name="id_sppt" value="{{ old('id_sppt') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NOP</label>
                            <select name="nop"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih NOP</option>
                                @foreach ($objek as $item)
                                    <option value="{{ $item->nop }}" @selected(old('nop') == $item->nop)>{{ $item->nop }}
                                        - {{ $item->subjekPajak?->nama ?? $item->nik_pemilik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" name="tahun" min="1900" max="2100"
                                value="{{ old('tahun') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NJOP Bumi</label>
                            <input type="number" step="0.01" min="0" name="njop_bumi"
                                value="{{ old('njop_bumi') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NJOP Bangunan</label>
                            <input type="number" step="0.01" min="0" name="njop_bangunan"
                                value="{{ old('njop_bangunan') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pajak Terhutang</label>
                            <input type="number" step="0.01" min="0" name="pajak_terhutang"
                                value="{{ old('pajak_terhutang') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Bayar</label>
                            <select name="status_bayar"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="piutang" @selected(old('status_bayar') == 'piutang')>Piutang</option>
                                <option value="lunas" @selected(old('status_bayar') == 'lunas')>Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('sppt.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
