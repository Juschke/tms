@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Auftrag anzeigen</h1>

    <!-- Navigation zur Übersicht -->
    <div class="mb-3">
        <a href="{{ route('admin.auftraege.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left-circle"></i> Zurück
        </a>
        <a href="{{ route('admin.auftraege.edit', $auftrag->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Bearbeiten
        </a>
    </div>

    <!-- Row mit zwei Spalten -->
    <div class="row g-3 mb-4">
        <!-- Linke Spalte: Basisdaten -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Basisdaten</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>ID:</strong> {{ $auftrag->id }}
                    </div>
                    <div class="mb-2">
                        <strong>Auftragsnummer:</strong> {{ $auftrag->auftragsnummer }}
                    </div>
                    <div class="mb-2">
                        <strong>Kunde:</strong> 
                        @if($auftrag->kunde)
                            {{ $auftrag->kunde->firmenname }}
                        @else
                            <em>- kein Kunde zugewiesen -</em>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Zugewiesener Benutzer:</strong> 
                        @if($auftrag->benutzer)
                            {{ $auftrag->benutzer->name }}
                        @else
                            <em>- niemand zugewiesen -</em>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Titel:</strong> {{ $auftrag->titel }}
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong> 
                        @php
                            $badgeClass = match($auftrag->status) {
                                'Offen' => 'bg-warning',
                                'InBearbeitung' => 'bg-info',
                                'Abgeschlossen' => 'bg-success',
                                'Storniert' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $auftrag->status }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Erstellungsdatum:</strong> {{ $auftrag->erstellungsdatum }}
                    </div>
                    <div class="mb-2">
                        <strong>Fällig am:</strong> {{ $auftrag->faellig_am }}
                    </div>
                    <div class="mb-2">
                        <strong>Erstellt am:</strong> {{ $auftrag->created_at }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Rechte Spalte: Finanzen, Sprachen, Priorität -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Finanzdaten & Priorität</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Preis gesamt:</strong> {{ number_format($auftrag->preis_gesamt, 2) }} €
                    </div>
                    <div class="mb-2">
                        <strong>Anzahlung:</strong> {{ number_format($auftrag->anzahlung, 2) }} €
                    </div>
                    <div class="mb-2">
                        <strong>Steuersatz:</strong> {{ $auftrag->steuersatz }} %
                    </div>
                    <div class="mb-2">
                        <strong>Rabatt (%):</strong> {{ $auftrag->rabatt_prozent }} %
                    </div>
                    <div class="mb-2">
                        <strong>Priorität:</strong> 
                        @php
                            $prioClass = match($auftrag->prioritaet) {
                                'Dringend' => 'text-danger fw-bold',
                                'Hoch' => 'text-warning fw-bold',
                                'Normal' => 'text-body',
                                'Niedrig' => 'text-muted',
                                default => 'text-body'
                            };
                        @endphp
                        <span class="{{ $prioClass }}">
                            {{ $auftrag->prioritaet }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Sprachen & Sonstiges</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Quellsprache:</strong> {{ $auftrag->quell_sprache }}
                    </div>
                    <div class="mb-2">
                        <strong>Zielsprache:</strong> {{ $auftrag->ziel_sprache }}
                    </div>
                    <div class="mb-2">
                        <strong>Standort:</strong> {{ $auftrag->standort }}
                    </div>
                    <div class="mb-2">
                        <strong>Hochgeladene Datei:</strong> 
                        @if($auftrag->hochgeladene_datei)
                            <a href="{{ asset('uploads/'.$auftrag->hochgeladene_datei) }}" target="_blank">
                                {{ $auftrag->hochgeladene_datei }}
                            </a>
                        @else
                            <em>- keine Datei -</em>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accordion: Partner zuweisen, PDF hochladen, etc. -->
    <div class="accordion" id="auftragAccordion">
        <!-- Beispiel: Partner-Zuweisung -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="partnerHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePartner" aria-expanded="false" aria-controls="collapsePartner">
                    <i class="bi bi-people me-2"></i> Partner zuweisen (Übersetzer/Dolmetscher)
                </button>
            </h2>
            <div id="collapsePartner" class="accordion-collapse collapse" aria-labelledby="partnerHeading" data-bs-parent="#auftragAccordion">
                <div class="accordion-body">
                    <!-- Hier könntest du eine Liste bereits zugewiesener Partner anzeigen -->
                    <!-- Dann ein Form für das Zuweisen eines neuen Partners -->
                    <form action="{{ route('admin.auftraege.assignPartner', $auftrag->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="partner_id" class="form-label">Partner auswählen</label>
                                <select name="partner_id" id="partner_id" class="form-select">
                                    <option value="">-- bitte wählen --</option>
                                    @foreach($partner as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="partner_role" class="form-label">Rolle</label>
                                <select name="partner_role" id="partner_role" class="form-select">
                                    <option value="Übersetzer">Übersetzer</option>
                                    <option value="Dolmetscher">Dolmetscher</option>
                                    <option value="Übersetzer & Dolmetscher">Übersetzer & Dolmetscher</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Partner zuweisen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Beispiel: PDF-Datei hochladen -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="pdfHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePdf" aria-expanded="false" aria-controls="collapsePdf">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i> PDF hochladen
                </button>
            </h2>
            <div id="collapsePdf" class="accordion-collapse collapse" aria-labelledby="pdfHeading" data-bs-parent="#auftragAccordion">
                <div class="accordion-body">
                    <form action="" method="POST" enctype="multipart/form-data"> 
                        @csrf
                        <div class="mb-3">
                            <label for="pdf_file" class="form-label">Wähle eine PDF-Datei</label>
                            <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept="application/pdf">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Hochladen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Beispiel: Direkt PDF generieren/anzeigen -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="pdfGenerateHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePdfGenerate" aria-expanded="false" aria-controls="collapsePdfGenerate">
                    <i class="bi bi-file-earmark-pdf me-2"></i> PDF generieren (Rechnung o.Ä.)
                </button>
            </h2>
            <div id="collapsePdfGenerate" class="accordion-collapse collapse" aria-labelledby="pdfGenerateHeading" data-bs-parent="#auftragAccordion">
                <div class="accordion-body">
                    <form action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-file-earmark-pdf"></i> PDF erzeugen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
