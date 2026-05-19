<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Pembayaran</h2>
                <p class="text-sm text-gray-500">Perbarui detail pembayaran SPPT.</p>
            </div>
            <a href="{{ route('pembayaran.index') }}"
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

                <form method="POST" action="{{ route('pembayaran.update', $pembayaran->id_bayar) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Bayar</label>
                            <input type="text" name="id_bayar" value="{{ old('id_bayar', $pembayaran->id_bayar) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SPPT</label>
                            <select name="id_sppt"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih SPPT</option>
                                @foreach ($sppt as $item)
                                    <option value="{{ $item->id_sppt }}" @selected(old('id_sppt', $pembayaran->id_sppt) == $item->id_sppt)>
                                        {{ $item->id_sppt }} - {{ $item->nop }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar"
                                value="{{ old('tgl_bayar', $pembayaran->tgl_bayar->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                            <input type="number" step="0.01" min="0" name="jumlah_bayar"
                                value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Petugas</label>
                        <select name="id_petugas"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="">Pilih petugas</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('id_petugas', $pembayaran->id_petugas) == $user->id)>{{ $user->username }}
                                    ({{ $user->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pembayaran.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
