<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Einstellungen;

class EinstellungenController extends Controller
{
    /**
     * Zeigt die Übersicht aller Einstellungen.
     */
    public function index()
    {
        $settings = Einstellungen::first();
        return view('admin.einstellungen', compact('settings'));
    }

    /**
     * Formular zum Anlegen neuer Einstellungen.
     */
    public function create()
    {
        return view('admin.einstellungen.create');
    }

    /**
     * Speichert neue Einstellung in der Datenbank.
     */
    public function store(Request $request)
    {
        // Hier Validierung & Speichern implementieren
        // $request->validate([...]);
        // Setting::create($request->all());
        
        return redirect()->route('admin.einstellungen.index')
                         ->with('success', 'Einstellung wurde erfolgreich angelegt.');
    }

    /**
     * Einzelne Einstellung anzeigen.
     */
    public function show($id)
    {
        // $setting = Setting::findOrFail($id);
        // return view('admin.einstellungen.show', compact('setting'));

        return view('admin.einstellungen.show');
    }

    /**
     * Formular zum Bearbeiten einer bestehenden Einstellung.
     */
    public function edit($id)
    {
        // $setting = Setting::findOrFail($id);
        // return view('admin.einstellungen.edit', compact('setting'));

        return view('admin.einstellungen.edit');
    }

    /**
     * Aktualisiert eine bestehende Einstellung.
     */
    public function update(Request $request, $id)
    {
        // $setting = Setting::findOrFail($id);
        // $request->validate([...]);
        // $setting->update($request->all());
        $settings = Einstellungen::firstOrCreate([]);
        $settings->update($request->all());
        return redirect()->route('admin.einstellungen.index')
                         ->with('success', 'Einstellung wurde erfolgreich aktualisiert.');
    }

    /**
     * Löscht eine Einstellung.
     */
    public function destroy($id)
    {
        // $setting = Setting::findOrFail($id);
        // $setting->delete();

        return redirect()->route('admin.einstellungen.index')
                         ->with('success', 'Einstellung wurde gelöscht.');
    }
}
