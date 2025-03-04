<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auftrag;
use App\Models\Kunde;
use App\Models\User;
use App\Models\Sprachen;
use App\Models\Partner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Einstellungen;

class AuftragController extends Controller
{
    public function index(Request $request)
    {
        $query = Auftrag::with('kunde');

        // Suchfilter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('auftragsnummer', 'LIKE', "%$search%")
                  ->orWhereHas('kunde', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%$search%");
                  });
        }

        // Status-Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Statistik-Werte berechnen
        $aktiveAuftraege = Auftrag::where('status', 'in_bearbeitung')->count();
        $ausstehendeAuftraege = Auftrag::where('status', 'offen')->count();
        $faelligeAuftraege = Auftrag::where('faellig_am', '<', now())->whereNotIn('status', ['abgeschlossen', 'storniert'])->count();
        $fertigeAuftraege = Auftrag::where('status', 'abgeschlossen')
                                    ->whereMonth('updated_at', now()->month)
                                    ->count();

        $auftraege = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.auftraege.index', compact('auftraege', 'aktiveAuftraege', 'ausstehendeAuftraege', 'faelligeAuftraege', 'fertigeAuftraege'));
    }

    public function create()
    {
        $kunden = Kunde::all();
        $benutzer = User::all();
        $sprachen = Sprachen::all(); // Alle Sprachen abrufen
        $partner = Partner::all();
        $autoGenNumber = $this->generateAuftragsnummer();
        return view('admin.auftraege.create', compact('kunden', 'benutzer', 'sprachen','partner','autoGenNumber' ));
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
           // 'auftragsnummer'    => 'nullable|unique:auftraege,auftragsnummer',
            'kunde_id'          => 'required|exists:kunden,id',
            'titel'             => 'required',
            'status'            => 'required|in:Neu,InBearbeitung,Abgeschlossen,Storniert',
            'prioritaet'        => 'required|in:Niedrig,Normal,Hoch,Dringend',
            'quell_sprache'     => 'required',
            'ziel_sprache'      => 'required',
            'erstellungsdatum'  => 'nullable|date',
            'faellig_am'        => 'required|date',
            'preis_gesamt'      => 'required|numeric|min:0',
            'anzahlung'         => 'nullable|numeric|min:0',
            'steuersatz'        => 'nullable|numeric|min:0',
            'rabatt_prozent'    => 'nullable|numeric|min:0',
            'hochgeladene_datei'=> 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'standort'          => 'nullable|string|max:255',
    
            // Positionen-Arrays (nicht zwingend validiert, nur minimal)
            'beschreibung.*'    => 'nullable|string',
            'menge.*'           => 'nullable|numeric',
            'preis.*'           => 'nullable|numeric',
            'mwst.*'            => 'nullable|numeric',
            'einheit.*'         => 'nullable|string',
        ]);
    
        if (!$request->filled('erstellungsdatum')) {
            $validatedData['erstellungsdatum'] = now()->toDateString();
        }
    
        if ($request->hasFile('hochgeladene_datei')) {
            $filePath = $request->file('hochgeladene_datei')->store('uploads/auftraege', 'public');
            $validatedData['hochgeladene_datei'] = $filePath;
        }
    
        $validatedData['zugewiesen_user_id'] = $request->zugewiesen_user_id ?? null;
        $validatedData['anzahlung']         = $request->anzahlung         ?? 0.00;
        $validatedData['steuersatz']        = $request->steuersatz        ?? 19.00;
        $validatedData['rabatt_prozent']    = $request->rabatt_prozent    ?? 0.00;
        $validatedData['geloescht_markiert'] = 0;
        $validatedData['auftragsnummer'] = $this->generateAuftragsnummer();
    
        // Auftrag speichern
        $auftrag = Auftrag::create($validatedData);
    
        /* Positionen in eigener Tabelle speichern
        if ($request->has('beschreibung')) {
            foreach ($request->beschreibung as $i => $txt) {
                AuftragPosition::create([
                    'auftrag_id'   => $auftrag->id,
                    'beschreibung' => $txt,
                    'menge'        => $request->menge[$i]  ?? 1,
                    'preis'        => $request->preis[$i]  ?? 0,
                    'mwst'         => $request->mwst[$i]   ?? 0,
                    'einheit'      => $request->einheit[$i] ?? 'Stück',
                ]);
            }
        }
    */
        return redirect()->route('admin.auftraege.index')
        ->with('success', 'Auftrag erfolgreich angelegt. Auftrags-Nr: '.$validatedData['auftragsnummer']);
    }
    /**
     * Generiert eine eindeutige Auftragsnummer im Format "A2023-000123".
     */
    protected function generateAuftragsnummer()
    {
        // Jahr+Monat z.B. "2304"
        $yyMM = date('ym');
    
        // Alle Aufträge dieses Monats zählen
        $currentMonthCount = Auftrag::whereYear('created_at', date('Y'))
                                    ->whereMonth('created_at', date('m'))
                                    ->count() + 1;
        // => 1,2,3,...
    
        // Mit führenden Nullen auf 4 Zeichen, z.B. "0007"
        $paddedCount = str_pad($currentMonthCount, 4, '0', STR_PAD_LEFT);
    
        // Fertige Nummer: AN-2304-0001
        $auftragsNummer = 'AN-' . $yyMM . '-' . $paddedCount;
    
        return $auftragsNummer;
    }
        
    public function generateInvoice($id)
    {
        $auftrag = Auftrag::findOrFail($id);
        $settings = Einstellungen::first();
    
        $pdf = Pdf::loadView('admin.auftraege.invoice', compact('auftrag', 'settings'));
    
        return $pdf->stream("Rechnung_{$auftrag->auftragsnummer}.pdf");
    }

public function downloadInvoice($id)
{
    $auftrag = Auftrag::findOrFail($id);
    $settings = Einstellungen::first();

    $pdf = Pdf::loadView('admin.auftraege.invoice', compact('auftrag', 'settings'));

    return $pdf->download("Rechnung_{$auftrag->auftragsnummer}.pdf");
}
    public function preview(Request $request)
{
    $pdf = Pdf::loadView('admin.auftraege.pdf-preview', [
        'data' => $request->all()
    ]);

    return $pdf->stream();
}
    public function show($id)
    {
        $auftrag = Auftrag::with('kunde', 'benutzer')->findOrFail($id);
        $partner = Partner::all();

        return view('admin.auftraege.show', ['auftrag' => $auftrag,'partner' => $partner,]);
    }

    public function edit($id)
    {
        $auftrag = Auftrag::findOrFail($id);
        $kunden = Kunde::all();
        $benutzer = User::all();
        $sprachen = Sprachen::all();
        $partner = Partner::all();

        return view('admin.auftraege.edit', compact('auftrag', 'kunden', 'benutzer','sprachen','partner'));
    }

    public function update(Request $request, $id)
    {
        $auftrag = Auftrag::findOrFail($id);

        $request->validate([
            'auftragsnummer' => 'nullable|unique:auftraege,auftragsnummer,'.$auftrag->id,
            'kunde_id'       => 'required|exists:kunden,id',
            'titel'          => 'required',
            'status'         => 'required',
        ]);

        $auftrag->update($request->all());

        return redirect()->route('admin.auftraege.index')
                         ->with('success', 'Auftrag erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        $auftrag = Auftrag::findOrFail($id);
        $auftrag->delete();

        return redirect()->route('admin.auftraege.index')
                         ->with('success', 'Auftrag wurde gelöscht.');
    }

    public function assignPartner(Auftrag $auftrag, Request $request)
    {
        // Beispiel: partner_id in Tabelle 'auftraege'
        $data = $request->validate([
            'partner_id' => 'required|exists:partner,id',
        ]);
    
        // Partner speichern
        $auftrag->update([
            'partner_id' => $data['partner_id'],
        ]);
    
        return redirect()
            ->route('admin.auftraege.show', $auftrag->id)
            ->with('success', 'Partner wurde erfolgreich zugewiesen.');
    }
}
