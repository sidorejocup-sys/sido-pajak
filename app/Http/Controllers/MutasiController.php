<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiRequest;
use App\Models\Mutasi;
use App\Models\ObjekPajak;
use App\Models\SubjekPajak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MutasiController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Mutasi::class, 'mutasi');
    }

    public function index(Request $request): View
    {
        $query = Mutasi::with(['objekPajak', 'subjekLama', 'subjekBaru'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_mutasi', 'ilike', "%{$search}%")
                        ->orWhere('nop_asal', 'ilike', "%{$search}%")
                        ->orWhere('nik_lama', 'ilike', "%{$search}%")
                        ->orWhere('nik_baru', 'ilike', "%{$search}%")
                        ->orWhere('jenis_mutasi', 'ilike', "%{$search}%");
                });
            });

        $sortables = ['id_mutasi', 'nop_asal', 'nik_lama', 'nik_baru', 'jenis_mutasi', 'tgl_mutasi'];
        if (in_array($request->sort, $sortables)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $mutasi = $query->paginate(15)->withQueryString();

        return view('sidopajak.mutasi.index', compact('mutasi'));
    }

    public function create(): View
    {
        $objek = ObjekPajak::orderBy('nop')->get();
        $subjek = SubjekPajak::orderBy('nama')->get();

        return view('sidopajak.mutasi.create', compact('objek', 'subjek'));
    }

    public function store(MutasiRequest $request): RedirectResponse
    {
        Mutasi::create($request->validated());

        return redirect()->route('mutasi.index')->with('success', 'Riwayat mutasi berhasil disimpan.');
    }

    public function edit(Mutasi $mutasi): View
    {
        $objek = ObjekPajak::orderBy('nop')->get();
        $subjek = SubjekPajak::orderBy('nama')->get();

        return view('sidopajak.mutasi.edit', compact('mutasi', 'objek', 'subjek'));
    }

    public function update(MutasiRequest $request, Mutasi $mutasi): RedirectResponse
    {
        $mutasi->update($request->validated());

        return redirect()->route('mutasi.index')->with('success', 'Riwayat mutasi berhasil diperbarui.');
    }

    public function destroy(Mutasi $mutasi): RedirectResponse
    {
        $mutasi->delete();

        return back()->with('success', 'Catatan mutasi berhasil dihapus.');
    }
}
