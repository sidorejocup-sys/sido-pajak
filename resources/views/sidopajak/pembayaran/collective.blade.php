<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pembayaran Kolektif SPPT</h2>
                <p class="text-sm text-gray-500">Pilih beberapa SPPT untuk dibayar sekaligus berdasarkan subjek pajak.
                </p>
            </div>
            <a href="{{ route('pembayaran.index') }}"
                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                @if (session('success'))
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="filter-form" action="{{ route('pembayaran.collective') }}" method="GET"
                    class="grid gap-4 sm:grid-cols-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cari Subjek</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIK atau nama"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RT</label>
                        <select name="rt"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua RT</option>
                            @foreach ($rtRwList->groupBy('rt') as $rt => $items)
                                <option value="{{ $rt }}" @selected(request('rt') == $rt)>{{ $rt }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RW</label>
                        <select name="rw"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua RW</option>
                            @if (request('rt'))
                                @foreach ($rtRwList->where('rt', request('rt'))->groupBy('rw') as $rw => $items)
                                    <option value="{{ $rw }}" @selected(request('rw') == $rw)>{{ $rw }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900">Cari</button>
                    </div>
                </form>

                <form id="payment-form" action="{{ route('pembayaran.collective.store') }}" method="POST"
                    class="space-y-6">
                    @csrf

                    {{-- Store previously selected SPPT IDs --}}
                    @foreach (explode(',', request('selected_sppts', '')) as $spptId)
                        @if ($spptId)
                            <input type="hidden" name="preserved_sppts[]" value="{{ $spptId }}" />
                        @endif
                    @endforeach

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Daftar SPPT Berdasarkan Subjek Pajak</h3>
                            <button type="button" onclick="toggleSelectAll()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">Pilih
                                Semua Subjek</button>
                        </div>

                        @forelse ($spptGrouped as $subjekNik => $group)
                            <div class="border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
                                {{-- Subject Header with Checkbox --}}
                                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                    <div class="flex items-center gap-3 flex-1">
                                        <input type="checkbox"
                                            class="subjek-checkbox h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            data-subjek="{{ $subjekNik }}"
                                            onchange="toggleSubjekSppt(this, '{{ $subjekNik }}')" />
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $group['subjek']?->nik }} -
                                                {{ $group['subjek']?->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $group['subjek']?->alamat }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right text-sm">
                                        <p class="text-gray-600"><span
                                                class="font-semibold">{{ $group['jumlah_sppt'] }}</span> SPPT</p>
                                        <p class="text-gray-600">Rp <span
                                                class="font-semibold">{{ number_format($group['total_terhutang'], 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- SPPT Items for this Subject --}}
                                <div class="space-y-2 pl-8">
                                    @foreach ($group['sppt'] as $item)
                                        <div
                                            class="flex items-center gap-3 p-2 bg-white rounded border border-gray-100">
                                            <input type="checkbox" name="sppt_ids[]" value="{{ $item->id_sppt }}"
                                                class="sppt-checkbox-{{ $subjekNik }} h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                data-subjek="{{ $subjekNik }}" />
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">{{ $item->id_sppt }}</p>
                                                <p class="text-xs text-gray-500">NOP: {{ $item->nop }} | Tahun:
                                                    {{ $item->tahun }}</p>
                                            </div>
                                            <p class="text-sm font-medium text-gray-700">Rp
                                                {{ number_format($item->pajak_terhutang, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-center text-sm text-yellow-700">Tidak ada SPPT piutang untuk kriteria
                                    pencarian yang dipilih.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 pt-4 border-t border-gray-200">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar"
                                value="{{ old('tgl_bayar', now()->toDateString()) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Petugas</label>
                            <select name="id_petugas"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Pilih petugas</option>
                                @foreach (App\Models\User::orderBy('username')->get() as $user)
                                    <option value="{{ $user->id }}" @selected(old('id_petugas') == $user->id)>
                                        {{ $user->username }} ({{ $user->role }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('pembayaran.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Bayar
                            Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle all subjects and their SPPTs
        function toggleSelectAll() {
            const subjekCheckboxes = document.querySelectorAll('.subjek-checkbox');
            const allSelected = Array.from(subjekCheckboxes).every(cb => cb.checked);

            subjekCheckboxes.forEach(checkbox => {
                checkbox.checked = !allSelected;
                const subjekNik = checkbox.dataset.subjek;
                toggleSubjekSppt(checkbox, subjekNik);
            });
        }

        // Toggle all SPPT checkboxes for a specific subject
        function toggleSubjekSppt(checkbox, subjekNik) {
            const spptCheckboxes = document.querySelectorAll(`.sppt-checkbox-${subjekNik}`);
            spptCheckboxes.forEach(spptCheckbox => {
                spptCheckbox.checked = checkbox.checked;
            });
        }

        // When an individual SPPT checkbox changes, update the subject checkbox state
        document.querySelectorAll('[class^="sppt-checkbox-"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const subjekNik = this.dataset.subjek;
                const subjekCheckbox = document.querySelector(
                    `[data-subjek="${subjekNik}"].subjek-checkbox`);
                const spptCheckboxes = document.querySelectorAll(`.sppt-checkbox-${subjekNik}`);
                const allChecked = Array.from(spptCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(spptCheckboxes).some(cb => cb.checked);

                if (allChecked) {
                    subjekCheckbox.checked = true;
                    subjekCheckbox.indeterminate = false;
                } else if (someChecked) {
                    subjekCheckbox.indeterminate = true;
                } else {
                    subjekCheckbox.checked = false;
                    subjekCheckbox.indeterminate = false;
                }

                // Update filter form to preserve selected SPPTs
                updatePreservedSppts();
            });
        });

        // Preserve selected SPPTs when filtering
        function updatePreservedSppts() {
            const selectedSppts = Array.from(document.querySelectorAll('input[name="sppt_ids[]"]:checked'))
                .map(cb => cb.value)
                .join(',');

            const filterForm = document.getElementById('filter-form');
            let input = filterForm.querySelector('input[name="selected_sppts"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_sppts';
                filterForm.appendChild(input);
            }
            input.value = selectedSppts;
        }

        // Restore previously selected SPPTs when page loads
        window.addEventListener('load', function() {
            const preserved = document.querySelectorAll('input[name="preserved_sppts[]"]');
            preserved.forEach(input => {
                const checkbox = document.querySelector(`input[name="sppt_ids[]"][value="${input.value}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    // Trigger change event to update subject checkbox
                    checkbox.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });
        });

        // Add selected_sppts to filter form on submit
        document.getElementById('filter-form').addEventListener('submit', function() {
            updatePreservedSppts();
        });
    </script>
</x-app-layout>
