<?php

namespace App\Http\Controllers;

use App\Exports\SpptExport;
use App\Http\Requests\SpptRequest;
use App\Imports\SpptImport;
use App\Models\ObjekPajak;
use App\Models\Sppt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SpptController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Sppt::class, 'sppt');
    }

    public function index(Request $request): View
    {
        $query = Sppt::with('objekPajak')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_sppt', 'ilike', "%{$search}%")
                        ->orWhere('nop', 'ilike', "%{$search}%")
                        ->orWhere('tahun', 'ilike', "%{$search}%")
                        ->orWhere('status_bayar', 'ilike', "%{$search}%");
                });
            });

        $sortables = ['id_sppt', 'nop', 'tahun', 'pajak_terhutang', 'status_bayar'];
        if (in_array($request->sort, $sortables)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $sppt = $query->paginate(15)->withQueryString();

        return view('sidopajak.sppt.index', compact('sppt'));
    }

    public function create(): View
    {
        $objek = ObjekPajak::orderBy('nop')->get();

        return view('sidopajak.sppt.create', compact('objek'));
    }

    public function store(SpptRequest $request): RedirectResponse
    {
        Sppt::create($request->validated());

        return redirect()->route('sppt.index')->with('success', 'SPPT berhasil disimpan.');
    }

    public function edit(Sppt $sppt): View
    {
        $objek = ObjekPajak::orderBy('nop')->get();

        return view('sidopajak.sppt.edit', ['sppt' => $sppt, 'objek' => $objek]);
    }

    public function update(SpptRequest $request, Sppt $sppt): RedirectResponse
    {
        $sppt->update($request->validated());

        return redirect()->route('sppt.index')->with('success', 'Data SPPT berhasil diperbarui.');
    }

    public function destroy(Sppt $sppt): RedirectResponse
    {
        $sppt->delete();

        return back()->with('success', 'SPPT berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $this->authorize('import', Sppt::class);

        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480']]);

        Storage::disk('local')->makeDirectory('imports');
        $path = $request->file('file')->store('imports');
        Excel::queueImport(new SpptImport(), $path, 'local');

        return back()->with('success', 'Impor file telah dijadwalkan. Worker akan memprosesnya dalam beberapa saat.');
    }

    public function export()
    {
        $this->authorize('export', Sppt::class);

        return Excel::download(new SpptExport(), 'sppt.xlsx');
    }

    public function pdf()
    {
        $this->authorize('pdf', Sppt::class);

        $sppt = Sppt::with('objekPajak')->orderBy('id_sppt')->get();
        $document = Pdf::loadView('sidopajak.sppt.pdf', compact('sppt'));

        return $document->download('sppt.pdf');
    }
}
