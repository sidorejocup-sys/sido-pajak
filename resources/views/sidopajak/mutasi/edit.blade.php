<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Mutasi</h2>
                <p class="text-sm text-gray-500">Perbarui informasi mutasi kepemilikan tanah.</p>
            </div>
            <a href="{{ route('mutasi.index') }}"
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

                <form method="POST" action="{{ route('mutasi.update', $mutasi->id_mutasi) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Mutasi</label>
                            <input type="text" name="id_mutasi" value="{{ old('id_mutasi', $mutasi->id_mutasi) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NOP Asal</label>
                            <select name="nop_asal"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih NOP</option>
                                @foreach ($objek as $item)
                                    <option value="{{ $item->nop }}" @selected(old('nop_asal', $mutasi->nop_asal) == $item->nop)>{{ $item->nop }}
                                        - {{ $item->subjekPajak?->nama ?? $item->nik_pemilik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pemilik Lama</label>
                            <select name="nik_lama"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih subjek pajak</option>
                                @foreach ($subjek as $item)
                                    <option value="{{ $item->nik }}" @selected(old('nik_lama', $mutasi->nik_lama) == $item->nik)>{{ $item->nik }}
                                        - {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pemilik Baru</label>
                            <select name="nik_baru"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih subjek pajak</option>
                                @foreach ($subjek as $item)
                                    <option value="{{ $item->nik }}" @selected(old('nik_baru', $mutasi->nik_baru) == $item->nik)>
                                        {{ $item->nik }} - {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Mutasi</label>
                            <select name="jenis_mutasi"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="jual" @selected(old('jenis_mutasi', $mutasi->jenis_mutasi) == 'jual')>Jual</option>
                                <option value="hibah" @selected(old('jenis_mutasi', $mutasi->jenis_mutasi) == 'hibah')>Hibah</option>
                                <option value="waris" @selected(old('jenis_mutasi', $mutasi->jenis_mutasi) == 'waris')>Waris</option>
                                <option value="lainnya" @selected(old('jenis_mutasi', $mutasi->jenis_mutasi) == 'lainnya')>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mutasi</label>
                            <input type="date" name="tgl_mutasi"
                                value="{{ old('tgl_mutasi', $mutasi->tgl_mutasi->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Arsip</label>
                            <input type="text" name="no_arsip" value="{{ old('no_arsip', $mutasi->no_arsip) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('mutasi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
