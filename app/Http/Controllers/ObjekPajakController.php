<?php

namespace App\Http\Controllers;

use App\Exports\ObjekPajakExport;
use App\Http\Requests\ObjekPajakRequest;
use App\Imports\ObjekPajakImport;
use App\Models\ObjekPajak;
use App\Models\SubjekPajak;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ObjekPajakController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ObjekPajak::class, 'objek_pajak');
    }

    public function index(Request $request): View
    {
        $query = ObjekPajak::with('subjekPajak')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nop', 'ilike', "%{$search}%")
                        ->orWhere('nik_pemilik', 'ilike', "%{$search}%")
                        ->orWhere('letak_objek', 'ilike', "%{$search}%")
                        ->orWhere('status_aktif', 'ilike', "%{$search}%")
                        ->orWhereHas('subjekPajak', function ($query) use ($search) {
                            $query->where('nama', 'ilike', "%{$search}%");
                        });
                });
            });

        $sortables = ['nop', 'nik_pemilik', 'letak_objek', 'status_aktif', 'luas_bumi', 'luas_bangunan'];
        if (in_array($request->sort, $sortables)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $objek = $query->paginate(15)->withQueryString();

        return view('sidopajak.objek.index', compact('objek'));
    }

    public function create(): View
    {
        $subjek = SubjekPajak::orderBy('nama')->get();

        return view('sidopajak.objek.create', compact('subjek'));
    }

    public function store(ObjekPajakRequest $request): RedirectResponse
    {
        ObjekPajak::create($request->validated());

        return redirect()->route('objek-pajak.index')->with('success', 'Objek pajak berhasil disimpan.');
    }

    public function edit(ObjekPajak $objekPajak): View
    {
        $subjek = SubjekPajak::orderBy('nama')->get();

        return view('sidopajak.objek.edit', ['objek' => $objekPajak, 'subjek' => $subjek]);
    }

    public function update(ObjekPajakRequest $request, ObjekPajak $objekPajak): RedirectResponse
    {
        $objekPajak->update($request->validated());

        return redirect()->route('objek-pajak.index')->with('success', 'Data objek pajak berhasil diperbarui.');
    }

    public function destroy(ObjekPajak $objekPajak): RedirectResponse
    {
        $objekPajak->delete();

        return back()->with('success', 'Objek pajak berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $this->authorize('import', ObjekPajak::class);

        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls,csv']]);

        $import = new ObjekPajakImport();
        $import->import($request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            return back()->with('error', 'Beberapa baris gagal diimpor. Periksa format file dan coba lagi.');
        }

        if ($import->getRowCount() === 0) {
            return back()->with('error', 'Tidak ada baris valid yang diimpor. Pastikan file menggunakan header template yang benar.');
        }

        return back()->with('success', "Impor berhasil. {$import->getRowCount()} baris diproses.");
    }

    public function export()
    {
        $this->authorize('export', ObjekPajak::class);

        return Excel::download(new ObjekPajakExport(), 'objek_pajak.xlsx');
    }

    public function pdf()
    {
        $this->authorize('pdf', ObjekPajak::class);

        $objek = ObjekPajak::with('subjekPajak')->orderBy('nop')->get();
        $document = Pdf::loadView('sidopajak.objek.pdf', compact('objek'));

        return $document->download('objek_pajak.pdf');
    }
}
