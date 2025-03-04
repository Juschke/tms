<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Zeigt eine Liste aller Partner
     */
    public function index()
    {
        // Aktuell sortiert nach Datum absteigend
        $partner = Partner::orderBy('created_at', 'desc')->get();
        return view('admin.partner.index', compact('partner'));
    }

    /**
     * Formular für neuen Partner
     */
    public function create()
    {
        return view('admin.partner.create');
    }

    /**
     * Speichert einen neuen Partner
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:partners,email'
        ]);

        Partner::create($request->all());

        return redirect()->route('admin.partner.index')
                         ->with('success', 'Partner wurde erfolgreich angelegt.');
    }

    /**
     * Einzelnen Partner anzeigen
     */
    public function show($id)
    {
        $partner = Partner::findOrFail($id);

        // Zeigt z. B. eine Liste der verknüpften Aufträge
        return view('admin.partner.show', compact('partner'));
    }

    /**
     * Formular zum Bearbeiten eines Partners
     */
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partner.edit', compact('partner'));
    }

    /**
     * Aktualisiert einen Partner
     */
    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:partners,email,'.$partner->id
        ]);

        $partner->update($request->all());

        return redirect()->route('admin.partner.index')
                         ->with('success', 'Partner wurde erfolgreich aktualisiert.');
    }

    /**
     * Löscht einen Partner
     */
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.partner.index')
                         ->with('success', 'Partner wurde gelöscht.');
    }
}
