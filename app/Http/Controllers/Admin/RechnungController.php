<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rechnung;
use App\Models\Auftrag;
use App\Models\Kunde;
use Illuminate\Http\Request;

class RechnungController extends Controller
{
    public function index()
    {
        $rechnungen = Rechnung::with('auftrag', 'kunde')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.rechnungen.index', compact('rechnungen'));
    }

    public function create()
    {
        $auftraege = Auftrag::all();
        $kunden = Kunde::all();

        return view('admin.rechnungen.create', compact('auftraege', 'kunden'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rechnungsnummer' => 'required|unique:rechnungen,rechnungsnummer',
            'auftrag_id'      => 'required|exists:auftraege,id',
            'kunde_id'        => 'required|exists:kunden,id',
        ]);

        Rechnung::create($request->all());

        return redirect()->route('admin.rechnungen.index')
                         ->with('success', 'Rechnung erfolgreich angelegt.');
    }

    public function show($id)
    {
        $rechnung = Rechnung::with('auftrag', 'kunde')->findOrFail($id);
        return view('admin.rechnungen.show', compact('rechnung'));
    }

    public function edit($id)
    {
        $rechnung = Rechnung::findOrFail($id);
        $auftraege = Auftrag::all();
        $kunden = Kunde::all();

        return view('admin.rechnungen.edit', compact('rechnung', 'auftraege', 'kunden'));
    }

    public function update(Request $request, $id)
    {
        $rechnung = Rechnung::findOrFail($id);

        $request->validate([
            'rechnungsnummer' => 'required|unique:rechnungen,rechnungsnummer,'.$rechnung->id,
            'auftrag_id'      => 'required|exists:auftraege,id',
            'kunde_id'        => 'required|exists:kunden,id',
        ]);

        $rechnung->update($request->all());

        return redirect()->route('admin.rechnungen.index')
                         ->with('success', 'Rechnung erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        $rechnung = Rechnung::findOrFail($id);
        $rechnung->delete();

        return redirect()->route('admin.rechnungen.index')
                         ->with('success', 'Rechnung wurde gelöscht.');
    }
}
