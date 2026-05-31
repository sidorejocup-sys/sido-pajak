<?php

namespace App\Http\Controllers;

use App\Exports\SubjekPajakExport;
use App\Http\Requests\SubjekPajakRequest;
use App\Imports\SubjekPajakImport;
use App\Models\SubjekPajak;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SubjekPajakController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SubjekPajak::class, 'subjek_pajak');
    }

    public function index(Request $request): View
    {
        $query = SubjekPajak::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nik', 'ilike', "%{$search}%")
                        ->orWhere('nama', 'ilike', "%{$search}%")
                        ->orWhere('alamat', 'ilike', "%{$search}%")
                        ->orWhere('no_hp', 'ilike', "%{$search}%");
                });
            })
            ->when($request->rt, function ($query, $rt) {
                $query->where('rt', $rt);
            })
            ->when($request->rw, function ($query, $rw) {
                $query->where('rw', $rw);
            });

        $sortables = ['nik', 'nama', 'alamat', 'rt', 'rw', 'no_hp'];
        if (in_array($request->sort, $sortables)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $subjek = $query->paginate(15)->withQueryString();

        return view('sidopajak.subjek.index', compact('subjek'));
    }

    public function create(): View
    {
        return view('sidopajak.subjek.create');
    }

    public function store(SubjekPajakRequest $request): RedirectResponse
    {
        SubjekPajak::create($request->validated());

        return redirect()->route('subjek-pajak.index')->with('success', 'Subjek pajak berhasil disimpan.');
    }

    public function edit(SubjekPajak $subjekPajak): View
    {
        return view('sidopajak.subjek.edit', ['subjek' => $subjekPajak]);
    }

    public function update(SubjekPajakRequest $request, SubjekPajak $subjekPajak): RedirectResponse
    {
        $subjekPajak->update($request->validated());

        return redirect()->route('subjek-pajak.index')->with('success', 'Data subjek pajak berhasil diperbarui.');
    }

    public function destroy(SubjekPajak $subjekPajak): RedirectResponse
    {
        $subjekPajak->delete();

        return back()->with('success', 'Subjek pajak berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $this->authorize('import', SubjekPajak::class);

        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480']]);

        Storage::disk('local')->makeDirectory('imports');
        $path = $request->file('file')->store('imports');
        Excel::queueImport(new SubjekPajakImport(), $path, 'local');

        return back()->with('success', 'Impor file telah dijadwalkan. Worker akan memprosesnya dalam beberapa saat.');
    }

    public function export()
    {
        $this->authorize('export', SubjekPajak::class);

        return Excel::download(new SubjekPajakExport(), 'subjek_pajak.xlsx');
    }

    public function pdf()
    {
        $this->authorize('pdf', SubjekPajak::class);

        $subjek = SubjekPajak::orderBy('nik')->get();
        $document = Pdf::loadView('sidopajak.subjek.pdf', compact('subjek'));

        return $document->download('subjek_pajak.pdf');
    }
}
