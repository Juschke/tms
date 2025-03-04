<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunde;
use Illuminate\Http\Request;

class KundeController extends Controller
{
    // Index: Liste anzeigen
    public function index()
    {
        $kunden = Kunde::orderBy('created_at', 'desc')->get();
        return view('admin.kunden.index', compact('kunden'));
    }

    // Formular für neuen Kunden
    public function create()
    {
        return view('admin.kunden.create');
    }

    // Neuen Kunden speichern
    public function store(Request $request)
    {
        $request->validate([
            'firmenname' => 'required',
            'kontakt_email' => 'required|email|unique:kunden,kontakt_email',
        ]);

        Kunde::create($request->all());

        return redirect()->route('admin.kunden.index')
                         ->with('success', 'Kunde wurde erfolgreich angelegt.');
    }

    // Einzelnen Kunden anzeigen (optional)
    public function show($id)
    {
        $kunde = Kunde::findOrFail($id);
        return view('admin.kunden.show', compact('kunde'));
    }

    // Formular zum Bearbeiten
    public function edit($id)
    {
        $kunde = Kunde::findOrFail($id);
        return view('admin.kunden.edit', compact('kunde'));
    }

    // Aktualisierung
    public function update(Request $request, $id)
    {
        $kunde = Kunde::findOrFail($id);

        $request->validate([
            'firmenname' => 'required',
            'kontakt_email' => 'required|email|unique:kunden,kontakt_email,'.$kunde->id,
        ]);

        $kunde->update($request->all());

        return redirect()->route('admin.kunden.index')
                         ->with('success', 'Kunde wurde erfolgreich aktualisiert.');
    }

    // Löschen
    public function destroy($id)
    {
        $kunde = Kunde::findOrFail($id);
        $kunde->delete();

        return redirect()->route('admin.kunden.index')
                         ->with('success', 'Kunde wurde gelöscht.');
    }
}
