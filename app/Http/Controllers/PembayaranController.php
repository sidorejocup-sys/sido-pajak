<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Sppt;
use App\Models\SubjekPajak;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Pembayaran::class, 'pembayaran');
    }

    public function index(Request $request): View
    {
        $query = Pembayaran::with(['sppt', 'petugas'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_bayar', 'ilike', "%{$search}%")
                        ->orWhere('id_sppt', 'ilike', "%{$search}%")
                        ->orWhere('id_petugas', 'ilike', "%{$search}%");
                });
            });

        $sortables = ['id_bayar', 'id_sppt', 'tgl_bayar', 'jumlah_bayar', 'id_petugas'];
        if (in_array($request->sort, $sortables)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $pembayaran = $query->paginate(15)->withQueryString();

        return view('sidopajak.pembayaran.index', compact('pembayaran'));
    }

    public function create(): View
    {
        $sppt = Sppt::orderBy('id_sppt')->get();
        $users = User::orderBy('username')->get();

        return view('sidopajak.pembayaran.create', compact('sppt', 'users'));
    }

    public function collective(Request $request): View
    {
        $this->authorize('create', Pembayaran::class);

        // Get all distinct RT/RW for filter
        $rtRwList = SubjekPajak::select('rt', 'rw')
            ->distinct()
            ->orderBy('rt')
            ->orderBy('rw')
            ->where('rt', '!=', null)
            ->where('rw', '!=', null)
            ->get()
            ->unique(fn($item) => $item->rt . $item->rw);

        // Get SPPT with filtering and grouping
        $spptQuery = Sppt::with(['objekPajak.subjekPajak'])
            ->where('status_bayar', 'piutang')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('objekPajak.subjekPajak', function ($query) use ($search) {
                    $query->where('nik', 'ilike', "%{$search}%")
                        ->orWhere('nama', 'ilike', "%{$search}%");
                });
            })
            ->when($request->rt && $request->rw, function ($query) use ($request) {
                $query->whereHas('objekPajak.subjekPajak', function ($query) use ($request) {
                    $query->where('rt', $request->rt)
                        ->where('rw', $request->rw);
                });
            });

        // Group SPPTs by subject pajak
        $spptGrouped = $spptQuery->orderBy('tahun', 'desc')->get()
            ->groupBy(fn($sppt) => $sppt->objekPajak?->subjekPajak?->nik)
            ->map(function ($items, $nik) {
                $subjek = $items->first()?->objekPajak?->subjekPajak;
                return [
                    'subjek' => $subjek,
                    'sppt' => $items,
                    'total_terhutang' => $items->sum('pajak_terhutang'),
                    'jumlah_sppt' => $items->count(),
                ];
            });

        return view('sidopajak.pembayaran.collective', compact('spptGrouped', 'rtRwList'));
    }

    public function storeCollective(Request $request): RedirectResponse
    {
        $this->authorize('create', Pembayaran::class);

        $data = $request->validate([
            'sppt_ids' => ['required', 'array', 'min:1'],
            'sppt_ids.*' => ['required', 'string', 'exists:sppt,id_sppt'],
            'tgl_bayar' => ['required', 'date'],
            'id_petugas' => ['required', 'integer', 'exists:users,id'],
        ]);

        $selectedSppt = Sppt::whereIn('id_sppt', $data['sppt_ids'])->where('status_bayar', 'piutang')->get();

        DB::transaction(function () use ($selectedSppt, $data) {
            foreach ($selectedSppt as $item) {
                Pembayaran::create([
                    'id_bayar' => Str::uuid()->toString(),
                    'id_sppt' => $item->id_sppt,
                    'tgl_bayar' => $data['tgl_bayar'],
                    'jumlah_bayar' => $item->pajak_terhutang,
                    'id_petugas' => $data['id_petugas'],
                ]);

                $item->status_bayar = 'lunas';
                $item->save();
            }
        });

        return redirect()->route('pembayaran.index')->with('success', count($selectedSppt) . ' pembayaran SPPT berhasil diproses.');
    }

    public function store(PembayaranRequest $request): RedirectResponse
    {
        Pembayaran::create($request->validated());

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function edit(Pembayaran $pembayaran): View
    {
        $sppt = Sppt::orderBy('id_sppt')->get();
        $users = User::orderBy('username')->get();

        return view('sidopajak.pembayaran.edit', compact('pembayaran', 'sppt', 'users'));
    }

    public function update(PembayaranRequest $request, Pembayaran $pembayaran): RedirectResponse
    {
        $pembayaran->update($request->validated());

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran): RedirectResponse
    {
        $pembayaran->delete();

        return back()->with('success', 'Pembayaran berhasil dihapus.');
    }
}
