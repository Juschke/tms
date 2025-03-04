<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitarbeiter;
use Illuminate\Http\Request;

class MitarbeiterController extends Controller
{
    // Index: Liste anzeigen
    public function index()
    {
        $mitarbeiter = Mitarbeiter::orderBy('created_at', 'desc')->get();
        return view('admin.mitarbeiter.index', compact('mitarbeiter'));
    }

    // Formular für neuen Mitarbeiter
    public function create()
    {
        return view('admin.mitarbeiter.create');
    }

    // Neuen Mitarbeiter speichern
    public function store(Request $request)
    {
        $request->validate([
            'vorname' => 'required',
            'nachname' => 'required',
            'email' => 'required|email|unique:mitarbeiters,email',
        ]);

        Mitarbeiter::create($request->all());

        return redirect()->route('admin.mitarbeiter.index')
                         ->with('success', 'Mitarbeiter wurde erfolgreich angelegt.');
    }

    // Einzelnen Mitarbeiter anzeigen
    public function show($id)
    {
        $mitarbeiter = Mitarbeiter::findOrFail($id);
        return view('admin.mitarbeiter.show', compact('mitarbeiter'));
    }

    // Formular zum Bearbeiten
    public function edit($id)
    {
        $mitarbeiter = Mitarbeiter::findOrFail($id);
        return view('admin.mitarbeiter.edit', compact('mitarbeiter'));
    }

    // Aktualisierung
    public function update(Request $request, $id)
    {
        $mitarbeiter = Mitarbeiter::findOrFail($id);

        $request->validate([
            'vorname' => 'required',
            'nachname' => 'required',
            'email' => 'required|email|unique:mitarbeiters,email,'.$mitarbeiter->id,
        ]);

        $mitarbeiter->update($request->all());

        return redirect()->route('admin.mitarbeiter.index')
                         ->with('success', 'Mitarbeiter wurde erfolgreich aktualisiert.');
    }

    // Löschen
    public function destroy($id)
    {
        $mitarbeiter = Mitarbeiter::findOrFail($id);
        $mitarbeiter->delete();

        return redirect()->route('admin.mitarbeiter.index')
                         ->with('success', 'Mitarbeiter wurde gelöscht.');
    }
}
