@extends('admin.layout')

@section('content')


<div class="container-fluid  h-100">

    <!-- Fixed Top Navigation -->
    <div class="sap-topnav sticky-top bg-white border-bottom mb-3 py-2" style="z-index:1000;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="sap-title mb-0">Neuen Auftrag anlegen</h1>

            <!-- Navigation / Tabs -->
            <div class="sap-section-nav-horizontal d-flex mx-auto">
                <a href="#auftragsdaten" class="sap-nav-link active mx-2">
                    <i class="bi bi-file-text sap-icon"></i> Auftragsdaten
                </a>
                <a href="#sprachen" class="sap-nav-link mx-2">
                    <i class="bi bi-translate sap-icon"></i> Sprachen
                </a>
                <a href="#kunde" class="sap-nav-link mx-2">
                    <i class="bi bi-people sap-icon"></i> Kunde
                </a>
                <a href="#preiskalkulation" class="sap-nav-link mx-2">
                    <i class="bi bi-cash-stack sap-icon"></i> Preiskalkulation
                </a>
                <a href="#dokumente" class="sap-nav-link mx-2">
                    <i class="bi bi-upload sap-icon"></i> Dokumente
                </a>
            </div>

            <div>
                <a href="{{ route('admin.auftraege.index') }}" class="sap-btn-ghost me-2">
                    <i class="bi bi-x-lg"></i> Abbrechen
                </a>
                <button type="submit" form="auftragForm" class="sap-btn">
                    <i class="bi bi-check-lg"></i> Speichern
                </button>
            </div>
        </div>
    </div>

    <!-- ACHTUNG: data-preview-url und data-csrf-token für die Live-PDF-Vorschau -->
    <form id="auftragForm"
          action="{{ route('admin.auftraege.store') }}"
          method="POST"
          enctype="multipart/form-data"
          data-preview-url="{{ route('admin.auftraege.preview') }}"
          data-csrf-token="{{ csrf_token() }}"
    >
        @csrf

        <div class="row">
            <!-- Main content - left side -->
            <div class="col-md-8">
                <div class="row">
                    <!-- Auftragsdaten -->
                    <div class="col-md-12 mb-3">
                        <div id="auftragsdaten" class="sap-card h-100">
                            <h2 class="sap-card-title">
                                <i class="bi bi-file-text"></i> Auftragsinformationen
                            </h2>

                            <div class="row align-items-center mb-2">
                                <div class="col-6 d-flex">
                                   
                                    <div class="sap-form-group">
                                        <div class="input-group">
                                            <label for="auftragsnummer" class="sap-label">Auftragsnummer</label>
                                            <div class="sap-tooltip" style="margin-bottom:2px;">
                                                <i class="bi bi-info-circle"></i>
                                                <span class="tooltip-text">Eindeutige Nummer zur Identifizierung des Auftrags</span>
                                            </div>
                                            <input type="text"
                                                   class="sap-form-control @error('auftragsnummer') is-invalid @enderror"
                                                   id="auftragsnummer"
                                                   value="{{ old('auftragsnummer', $autoGenNumber) }}"
                                                   disabled
                                            >
                                        </div>
                                        @error('auftragsnummer')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="titel" class="sap-label required">Titel / Beschreibung</label>
                                        <input type="text"
                                               class="sap-form-control @error('titel') is-invalid @enderror"
                                               id="titel"
                                               name="titel"
                                               value="{{ old('titel') }}"
                                               required
                                        >
                                        @error('titel')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Datum -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="erstellungsdatum" class="sap-label">Erstellungsdatum</label>
                                        <input type="date"
                                               class="sap-form-control @error('erstellungsdatum') is-invalid @enderror"
                                               id="erstellungsdatum"
                                               name="erstellungsdatum"
                                               value="{{ old('erstellungsdatum') }}"
                                               required
                                        >
                                        @error('erstellungsdatum')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <div class="input-group">
                                            <label for="faellig_am" class="sap-label">Fälligkeitsdatum</label>
                                            <div class="sap-tooltip">
                                                <i class="bi bi-info-circle"></i>
                                                <span class="tooltip-text">
                                                    Datum, bis wann der Auftrag abgeschlossen sein muss
                                                </span>
                                            </div>
                                            <input type="date"
                                                   class="sap-form-control @error('faellig_am') is-invalid @enderror"
                                                   id="faellig_am"
                                                   name="faellig_am"
                                                   value="{{ old('faellig_am') }}"
                                                   
                                            >
                                           
                                        </div>
                                        @error('faellig_am')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Priorität -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="status" class="sap-label required">Status</label>
                                        <select class="sap-form-control @error('status') is-invalid @enderror"
                                                id="status"
                                                name="status"
                                                required
                                        >
                                            <option value="">- Bitte wählen -</option>
                                            <option value="Neu"           {{ old('status') == 'Neu' ? 'selected' : '' }}>Neu</option>
                                            <option value="InBearbeitung" {{ old('status') == 'InBearbeitung' ? 'selected' : '' }}>In Bearbeitung</option>
                                            <option value="Abgeschlossen" {{ old('status') == 'Abgeschlossen' ? 'selected' : '' }}>Abgeschlossen</option>
                                            <option value="Storniert"     {{ old('status') == 'Storniert' ? 'selected' : '' }}>Storniert</option>
                                        </select>
                                        @error('status')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="prioritaet" class="sap-label required">Priorität</label>
                                        <select class="sap-form-control @error('prioritaet') is-invalid @enderror"
                                                id="prioritaet"
                                                name="prioritaet"
                                                required
                                        >
                                            <option value="">- Bitte wählen -</option>
                                            <option value="Niedrig"   {{ old('prioritaet') == 'Niedrig' ? 'selected' : '' }}>Niedrig</option>
                                            <option value="Normal"    {{ old('prioritaet') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="Hoch"      {{ old('prioritaet') == 'Hoch' ? 'selected' : '' }}>Hoch</option>
                                            <option value="Dringend"  {{ old('prioritaet') == 'Dringend' ? 'selected' : '' }}>Dringend</option>
                                        </select>
                                        @error('prioritaet')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Anmerkungen -->
                            <div class="sap-form-group">
                                <label for="anmerkungen" class="sap-label">Anmerkungen</label>
                                <textarea class="sap-form-control @error('anmerkungen') is-invalid @enderror"
                                          id="anmerkungen"
                                          name="beschreibung"
                                          rows="2"
                                >{{ old('anmerkungen') }}</textarea>
                                @error('anmerkungen')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div><!-- /Auftragsdaten -->

                    <!-- Sprachen & Kunde (in the same row as Auftragsdaten) -->
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <!-- Sprachen -->
                            <div class="col-12 mb-3">
                                <div id="sprachen" class="sap-card">
                                    <h2 class="sap-card-title">
                                        <i class="bi bi-translate"></i> Sprachen
                                    </h2>

                                    <!-- Nur noch ein Button "Sprachen hinzufügen" -->
                                    <div>
                                        <button type="button" class="sap-btn" id="openSprachenModal">
                                            <i class="bi bi-plus-circle"></i> Sprachen hinzufügen
                                        </button>
                                    </div>

                                    <!-- Container für die ausgewählten Sprachen (Mehrfachauswahl) -->
                                    <div class="mt-2" id="selectedSprachenContainer">
                                        @if(old('sprachen'))
                                            @foreach(old('sprachen') as $selectedId)
                                                @php
                                                    $spracheModel = $sprachen->firstWhere('id', $selectedId);
                                                    if(!$spracheModel) continue;
                                                @endphp
                                                <div class="p-1 border rounded d-inline-block me-1 mb-1">
                                                    {{ $spracheModel->bez_lang }} ({{ $spracheModel->bez_kurz }})
                                                    <input type="hidden" name="sprachen[]" value="{{ $spracheModel->id }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Quell- & Zielsprache (keine Plus-Icons mehr) -->
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="sap-form-group">
                                                <label for="quell_sprache" class="sap-label required">Quellsprache</label>
                                                <select class="sap-form-control @error('quell_sprache') is-invalid @enderror"
                                                        id="quell_sprache"
                                                        name="quell_sprache"
                                                        required
                                                >
                                                    <option value="">- Bitte wählen -</option>
                                                    @foreach($sprachen as $sprache)
                                                        <option value="{{ $sprache->id }}"
                                                            {{ old('quell_sprache') == $sprache->id ? 'selected' : '' }}>
                                                            {{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('quell_sprache')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="sap-form-group">
                                                <label for="ziel_sprache" class="sap-label required">Zielsprache</label>
                                                <select class="sap-form-control @error('ziel_sprache') is-invalid @enderror"
                                                        id="ziel_sprache"
                                                        name="ziel_sprache"
                                                        required
                                                >
                                                    <option value="">- Bitte wählen -</option>
                                                    @foreach($sprachen as $sprache)
                                                        <option value="{{ $sprache->id }}"
                                                            {{ old('ziel_sprache') == $sprache->id ? 'selected' : '' }}>
                                                            {{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('ziel_sprache')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kunde -->
                            <div class="col-12">
                                <div id="kunde" class="sap-card">
                                    <h2 class="sap-card-title">
                                        <i class="bi bi-people"></i> Kunde & Zuweisung
                                    </h2>

                                    <!-- Kunde Auswahl -->
                                    <div class="sap-form-group">
                                        <label for="kunde_id" class="sap-label required">Kunde</label>
                                        <div class="sap-search-container d-flex">
                                            <input type="text" class="sap-form-control" id="kunde_search" placeholder="Kunde suchen...">
                                            <button type="button" class="sap-btn ms-2" id="openKundenModal">
                                                <i class="bi bi-search"></i>
                                            </button>
                                            <input type="hidden" id="kunde_id" name="kunde_id" value="{{ old('kunde_id') }}">
                                        </div>
                                        <div id="selected_kunde" class="mt-2">
                                            @if(old('kunde_id'))
                                                <div class="p-2 border rounded">
                                                    {{ $kunden->firstWhere('id', old('kunde_id'))->firmenname ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                        @error('kunde_id')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Benutzer Zuweisung -->
                                    <div class="sap-form-group">
                                        <label for="benutzer_id" class="sap-label">Zuständiger Benutzer</label>
                                        <div class="sap-search-container d-flex">
                                            <input type="text" class="sap-form-control" id="benutzer_search" placeholder="Benutzer suchen...">
                                            <button type="button" class="sap-btn ms-2" id="openBenutzerModal">
                                                <i class="bi bi-search"></i>
                                            </button>
                                            <input type="hidden" id="benutzer_id" name="benutzer_id" value="{{ old('benutzer_id') }}">
                                        </div>
                                        <div id="selected_benutzer" class="mt-2">
                                            @if(old('benutzer_id'))
                                                <div class="p-2 border rounded">
                                                    {{ $benutzer->firstWhere('id', old('benutzer_id'))->name ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Partner Zuweisung -->
                                    <div class="sap-form-group">
                                        <label for="partner_id" class="sap-label">Externer Partner</label>
                                        <div class="sap-search-container d-flex">
                                            <input type="text" class="sap-form-control" id="partner_search" placeholder="Partner suchen...">
                                            <button type="button" class="sap-btn ms-2" id="openPartnerModal">
                                                <i class="bi bi-search"></i>
                                            </button>
                                            <input type="hidden" id="partner_id" name="partner_id" value="{{ old('partner_id') }}">
                                        </div>
                                        <div id="selected_partner" class="mt-2">
                                            @if(old('partner_id'))
                                                <div class="p-2 border rounded">
                                                    {{ $partner->firstWhere('id', old('partner_id'))->name ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Angerufen Checkbox -->
                                    <div class="sap-form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="angerufen"
                                                   name="angerufen"
                                                   value="1"
                                                   {{ old('angerufen') ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="angerufen">Angerufen</label>
                                            <div class="sap-tooltip">
                                                <i class="bi bi-info-circle"></i>
                                                <span class="tooltip-text">
                                                    Markieren Sie dieses Feld, wenn der Kunde bereits kontaktiert wurde
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preiskalkulation & Dokumente in a new row -->
                   

                    <div class="col-md-6 mb-3">
                        <div id="dokumente" class="sap-card h-100">
                            <h2 class="sap-card-title">
                                <i class="bi bi-upload"></i> Dokumente
                            </h2>

                            <div class="sap-form-group">
                                <label for="hochgeladene_datei" class="sap-label">Datei hochladen</label>
                                <input type="file"
                                       class="sap-form-control"
                                       id="hochgeladene_datei"
                                       name="hochgeladene_datei"
                                >
                                <div class="mt-2 small text-muted">
                                    Unterstützte Dateiformate: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (max. 10MB)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div id="preiskalkulation" class="sap-card h-100">
                            <h2 class="sap-card-title">
                                <i class="bi bi-cash-stack"></i> Preiskalkulation
                            </h2>

                            <!-- Tabelle für dynamische Positionen -->
                            <div class="table-responsive">
                                <table id="kostenTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Beschreibung</th>
                                            <th width="80">Menge</th>
                                            <th width="90">Einheit</th>
                                            <th width="100">Preis (€)</th>
                                            <th width="90">Steuer (%)</th>
                                            <th width="100">Gesamt (€)</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamische Zeilen werden hier hinzugefügt -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Button zum Hinzufügen neuer Positionen -->
                            <button type="button" class="sap-btn" id="addPositionBtn">
                                <i class="bi bi-plus-circle"></i> Position hinzufügen
                            </button>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="rabatt_prozent" class="sap-label">Rabatt (%)</label>
                                        <input type="number"
                                               step="0.01"
                                               class="sap-form-control"
                                               id="rabatt_prozent"
                                               name="rabatt_prozent"
                                               value="{{ old('rabatt_prozent') }}"
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="sap-form-group">
                                        <label for="anzahlung" class="sap-label">Anzahlung (€)</label>
                                        <input type="number"
                                               step="0.01"
                                               class="sap-form-control"
                                               id="anzahlung"
                                               name="anzahlung"
                                               value="{{ old('anzahlung') }}"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <h4>
                                    <strong>Gesamtbetrag: <span id="gesamtbetrag">0.00</span> €</strong>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live PDF-Preview (right side) -->
            <div class="col-md-4">
                <div class="h-100" style="">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">PDF-Vorschau</h5>
                        </div>
                        <div class="card-body p-0">
                            <iframe id="pdfPreview"
                                    class="w-100"
                                    style="border: none; height: calc(100vh - 140px);">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Field für den Gesamtpreis -->
        <input type="hidden" name="preis_gesamt" id="preis_gesamt_input">
    </form>
</div>

<!-- Footer mit Action-Buttons -->
<div class="sap-footer">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.auftraege.index') }}" class="sap-btn-secondary">
                <i class="bi bi-x-lg"></i> Abbrechen
            </a>
            <button type="submit" form="auftragForm" class="sap-btn">
                <i class="bi bi-check-lg"></i> Auftrag speichern
            </button>
        </div>
    </div>
</div>

<!-- Kunden-Suchmodal -->
<div class="sap-modal" id="kundenModal">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Kunde auswählen</h3>
            <button type="button" class="sap-modal-close" id="closeKundenModal">&times;</button>
        </div>

        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="kundenSuche" placeholder="Nach Firmenname oder Kontakt suchen...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Firmenname</th>
                        <th>Ansprechpartner</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunden as $kunde)
                        <tr>
                            <td>{{ $kunde->firmenname }}</td>
                            <td>{{ $kunde->ansprechpartner ?? '-' }}</td>
                            <td>
                                <button type="button"
                                        class="sap-btn-ghost select-kunde"
                                        data-id="{{ $kunde->id }}"
                                        data-name="{{ $kunde->firmenname }}">
                                    Auswählen
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-end">
            <a href="{{ route('admin.kunden.create') }}" class="sap-btn">
                <i class="bi bi-plus"></i> Neuen Kunden anlegen
            </a>
        </div>
    </div>
</div>

<!-- Modal für die Sprachen-Auswahl -->
<div class="sap-modal" id="sprachenModal" style="display: none;">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Sprachen auswählen</h3>
            <button type="button" class="sap-modal-close" id="closeSprachenModal">&times;</button>
        </div>

        <!-- Suchfeld -->
        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="sprachenSuche" placeholder="Nach Sprache suchen...">
        </div>

        <!-- Tabelle mit Checkboxen -->
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sprache</th>
                        <th>Auswählen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sprachen as $sprache)
                        <tr>
                            <td>{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})</td>
                            <td>
                                <input
                                    type="checkbox"
                                    class="form-check-input sprache-checkbox"
                                    value="{{ $sprache->id }}"
                                    data-name="{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-end">
            <button type="button" class="sap-btn" id="uebernehmenSprachenBtn">
                <i class="bi bi-check-lg"></i> Ausgewählte übernehmen
            </button>
        </div>
    </div>
</div>

<!-- Quell-Sprachen Modal -->
<div class="sap-modal" id="quellModal" style="display: none;">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Quellsprachen auswählen (max. 5)</h3>
            <button type="button" class="sap-modal-close" id="closeQuellModal">&times;</button>
        </div>

        <!-- Suchfeld -->
        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="quellSuche" placeholder="Nach Sprache suchen...">
        </div>

        <!-- Tabelle mit Checkboxen -->
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sprache</th>
                        <th>Auswählen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sprachen as $sprache)
                        <tr>
                            <td>{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})</td>
                            <td>
                                <input
                                    type="checkbox"
                                    class="form-check-input quell-checkbox"
                                    value="{{ $sprache->id }}"
                                    data-name="{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-end">
            <button type="button" class="sap-btn" id="uebernehmenQuellBtn">
                <i class="bi bi-check-lg"></i> Ausgewählte übernehmen
            </button>
        </div>
    </div>
</div>

<!-- Ziel-Sprachen Modal -->
<div class="sap-modal" id="zielModal" style="display: none;">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Zielsprachen auswählen (max. 5)</h3>
            <button type="button" class="sap-modal-close" id="closeZielModal">&times;</button>
        </div>

        <!-- Suchfeld -->
        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="zielSuche" placeholder="Nach Sprache suchen...">
        </div>

        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sprache</th>
                        <th>Auswählen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sprachen as $sprache)
                        <tr>
                            <td>{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})</td>
                            <td>
                                <input
                                    type="checkbox"
                                    class="form-check-input ziel-checkbox"
                                    value="{{ $sprache->id }}"
                                    data-name="{{ $sprache->bez_lang }} ({{ $sprache->bez_kurz }})"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-end">
            <button type="button" class="sap-btn" id="uebernehmenZielBtn">
                <i class="bi bi-check-lg"></i> Ausgewählte übernehmen
            </button>
        </div>
    </div>
</div>

<!-- Benutzer-Suchmodal -->
<div class="sap-modal" id="benutzerModal">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Benutzer auswählen</h3>
            <button type="button" class="sap-modal-close" id="closeBenutzerModal">&times;</button>
        </div>

        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="benutzerSuche" placeholder="Nach Name oder E-Mail suchen...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($benutzer ?? [] as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <button type="button"
                                        class="sap-btn-ghost select-benutzer"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}">
                                    Auswählen
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Partner-Suchmodal -->
<div class="sap-modal" id="partnerModal">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Partner auswählen</h3>
            <button type="button" class="sap-modal-close" id="closePartnerModal">&times;</button>
        </div>

        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="partnerSuche" placeholder="Nach Name oder Firma suchen...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Firma</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partner ?? [] as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->firma ?? '-' }}</td>
                            <td>
                                <button type="button"
                                        class="sap-btn-ghost select-partner"
                                        data-id="{{ $p->id }}"
                                        data-name="{{ $p->name }}">
                                    Auswählen
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    initNavigation();
    initKundenModal();
    initBenutzerModal();
    initPartnerModal();
    initSprachenModal();
    initPreiskalkulation();
    initPDFPreview();

    // Falls keine Position existiert, füge mindestens 1 Zeile hinzu
    if (document.querySelector("#kostenTable tbody").children.length === 0) {
        addPosition();
    }
});

// ----------------------------------------------------------------------------
// Sprachen-Modal
// ----------------------------------------------------------------------------
function initSprachenModal() {
    const sprachenModal             = document.getElementById('sprachenModal');
    const openSprachenModalBtn      = document.getElementById('openSprachenModal');
    const closeSprachenModalBtn     = document.getElementById('closeSprachenModal');
    const uebernehmenSprachenBtn    = document.getElementById('uebernehmenSprachenBtn');
    const sprachenSuche             = document.getElementById('sprachenSuche');
    const sprachenTableRows         = document.querySelectorAll('#sprachenModal .table tbody tr');
    const selectedSprachenContainer = document.getElementById('selectedSprachenContainer');

    if (!sprachenModal) return;

    // -- Modal öffnen --
    if (openSprachenModalBtn) {
        openSprachenModalBtn.addEventListener('click', () => {
            sprachenModal.style.display = 'flex';
        });
    }

    // -- Modal schließen --
    if (closeSprachenModalBtn) {
        closeSprachenModalBtn.addEventListener('click', () => {
            sprachenModal.style.display = 'none';
        });
    }
    // Klick außerhalb schließt das Modal
    window.addEventListener('click', (event) => {
        if (event.target === sprachenModal) {
            sprachenModal.style.display = 'none';
        }
    });

    // -- Suchfunktion --
    sprachenSuche.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        sprachenTableRows.forEach(row => {
            const text = row.cells[0].textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // -- Ausgewählte Sprachen übernehmen --
    uebernehmenSprachenBtn.addEventListener('click', function() {
        // Liste der bereits ausgewählten ID-Werte, damit wir Duplikate vermeiden
        const alreadySelectedIds = Array.from(selectedSprachenContainer.querySelectorAll('input[name="sprachen[]"]'))
            .map(input => input.value);

        // Alle angehakten Checkboxen durchgehen
        const checkboxes = document.querySelectorAll('.sprache-checkbox:checked');
        checkboxes.forEach(cb => {
            // Nur hinzufügen, wenn nicht schon ausgewählt
            if (!alreadySelectedIds.includes(cb.value)) {
                const chip = document.createElement('div');
                chip.classList.add('p-1', 'border', 'rounded', 'd-inline-block', 'me-1', 'mb-1');
                chip.textContent = cb.dataset.name; // Anzeigename

                // Verstecktes Input-Feld fürs Formular
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'sprachen[]';
                hiddenInput.value = cb.value;

                // Optional: Entfernen-Button (X)
                // const removeBtn = document.createElement('button');
                // removeBtn.type = 'button';
                // removeBtn.innerHTML = '&times;';
                // removeBtn.classList.add('ms-2', 'text-danger', 'border-0', 'bg-transparent');
                // removeBtn.addEventListener('click', () => chip.remove());

                chip.appendChild(hiddenInput);
                // chip.appendChild(removeBtn);

                selectedSprachenContainer.appendChild(chip);
            }
        });

        // Modal schließen
        sprachenModal.style.display = 'none';
    });
}

// ----------------------------------------------------------------------------
// Navigation (Tabs springen, aktiven Link setzen, Fokus) 
// ----------------------------------------------------------------------------
function initNavigation() {
    document.querySelectorAll(".sap-nav-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            document.querySelectorAll(".sap-nav-link").forEach(navLink => navLink.classList.remove("active"));
            this.classList.add("active");

            const targetId = this.getAttribute("href");
            const targetElement = document.querySelector(targetId);

            // Setze Fokus auf das erste Eingabefeld des Bereichs
            const firstInput = targetElement.querySelector("input, select, textarea");
            if (firstInput) firstInput.focus();

            // Scrolle zum Abschnitt
            targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });
}

// ----------------------------------------------------------------------------
// Kunden-Modal
// ----------------------------------------------------------------------------
function initKundenModal() {
    const kundenModal = document.getElementById("kundenModal");
    const openKundenModalBtn = document.getElementById("openKundenModal");
    const closeKundenModalBtn = document.getElementById("closeKundenModal");

    if (!kundenModal) return;

    if (openKundenModalBtn && closeKundenModalBtn) {
        openKundenModalBtn.addEventListener("click", () => kundenModal.style.display = "flex");
        closeKundenModalBtn.addEventListener("click", () => kundenModal.style.display = "none");
        window.addEventListener("click", (event) => {
            if (event.target === kundenModal) kundenModal.style.display = "none";
        });

        document.querySelectorAll(".select-kunde").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("kunde_id").value = this.dataset.id;
                document.getElementById("kunde_search").value = this.dataset.name;
                document.getElementById("selected_kunde").innerHTML =
                    `<div class="p-2 border rounded">${this.dataset.name}</div>`;
                kundenModal.style.display = "none";
            });
        });
    }

    // Kunde-Suche
    document.getElementById("kundenSuche").addEventListener("keyup", function () {
        const searchText = this.value.toLowerCase();
        document.querySelectorAll("#kundenModal .table tbody tr").forEach(row => {
            const firmenname = row.cells[0].textContent.toLowerCase();
            const ansprechpartner = row.cells[1].textContent.toLowerCase();
            row.style.display = (firmenname.includes(searchText) || ansprechpartner.includes(searchText)) ? "" : "none";
        });
    });
}

// ----------------------------------------------------------------------------
// Benutzer-Modal
// ----------------------------------------------------------------------------
function initBenutzerModal() {
    const benutzerModal = document.getElementById("benutzerModal");
    const openBenutzerModalBtn = document.getElementById("openBenutzerModal");
    const closeBenutzerModalBtn = document.getElementById("closeBenutzerModal");

    if (!benutzerModal) return;

    if (openBenutzerModalBtn && closeBenutzerModalBtn) {
        openBenutzerModalBtn.addEventListener("click", () => benutzerModal.style.display = "flex");
        closeBenutzerModalBtn.addEventListener("click", () => benutzerModal.style.display = "none");
        window.addEventListener("click", (event) => {
            if (event.target === benutzerModal) benutzerModal.style.display = "none";
        });

        document.querySelectorAll(".select-benutzer").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("benutzer_id").value = this.dataset.id;
                document.getElementById("benutzer_search").value = this.dataset.name;
                document.getElementById("selected_benutzer").innerHTML =
                    `<div class="p-2 border rounded">${this.dataset.name}</div>`;
                benutzerModal.style.display = "none";
            });
        });
    }

    // Benutzer-Suche
    document.getElementById("benutzerSuche").addEventListener("keyup", function () {
        const searchText = this.value.toLowerCase();
        document.querySelectorAll("#benutzerModal .table tbody tr").forEach(row => {
            const name  = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            row.style.display = (name.includes(searchText) || email.includes(searchText)) ? "" : "none";
        });
    });
}

// ----------------------------------------------------------------------------
// Partner-Modal
// ----------------------------------------------------------------------------
function initPartnerModal() {
    const partnerModal = document.getElementById("partnerModal");
    const openPartnerModalBtn = document.getElementById("openPartnerModal");
    const closePartnerModalBtn = document.getElementById("closePartnerModal");

    if (!partnerModal) return;

    if (openPartnerModalBtn && closePartnerModalBtn) {
        openPartnerModalBtn.addEventListener("click", () => partnerModal.style.display = "flex");
        closePartnerModalBtn.addEventListener("click", () => partnerModal.style.display = "none");
        window.addEventListener("click", (event) => {
            if (event.target === partnerModal) partnerModal.style.display = "none";
        });

        document.querySelectorAll(".select-partner").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("partner_id").value = this.dataset.id;
                document.getElementById("partner_search").value = this.dataset.name;
                document.getElementById("selected_partner").innerHTML =
                    `<div class="p-2 border rounded">${this.dataset.name}</div>`;
                partnerModal.style.display = "none";
            });
        });
    }

    // Partner-Suche
    document.getElementById("partnerSuche").addEventListener("keyup", function () {
        const searchText = this.value.toLowerCase();
        document.querySelectorAll("#partnerModal .table tbody tr").forEach(row => {
            const name  = row.cells[0].textContent.toLowerCase();
            const firma = row.cells[1].textContent.toLowerCase();
            row.style.display = (name.includes(searchText) || firma.includes(searchText)) ? "" : "none";
        });
    });
}

// ----------------------------------------------------------------------------
// Dynamische Preiskalkulation
// ----------------------------------------------------------------------------
function initPreiskalkulation() {
    document.getElementById("rabatt_prozent").addEventListener("input", updateTotal);
    document.getElementById("anzahlung").addEventListener("input", updateTotal);
    document.getElementById("addPositionBtn").addEventListener("click", addPosition);
    attachEventListeners();
}

// Neue Position hinzufügen
function addPosition() {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td><input type="text" name="beschreibung[]" class="sap-form-control" required></td>
        <td><input type="number" name="menge[]" class="sap-form-control menge" value="1" min="1" required></td>
        <td>
            <select name="einheit[]" class="sap-form-control">
                <option value="Stück">Stück</option>
                <option value="Seite">Seite</option>
                <option value="Zeile">Zeile</option>
                <option value="Stunde">Stunde</option>
            </select>
        </td>
        <td><input type="number" name="preis[]" class="sap-form-control preis" step="0.01" required></td>
        <td>
            <select name="mwst[]" class="sap-form-control mwst">
                <option value="0">0%</option>
                <option value="7">7%</option>
                <option value="19">19%</option>
            </select>
        </td>
        <td class="gesamtpreis">0.00 €</td>
        <td>
            <button type="button" class="sap-btn-ghost text-danger" onclick="removeRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    document.querySelector("#kostenTable tbody").appendChild(row);
    attachEventListeners();
    updateTotal();
}

// Position entfernen
function removeRow(button) {
    button.closest("tr").remove();
    updateTotal();
}

// Event-Listener an Mengen-, Preis- und MwSt-Felder hängen
function attachEventListeners() {
    document.querySelectorAll(".menge, .preis, .mwst").forEach(input => {
        input.removeEventListener("input", updateTotal);
        input.addEventListener("input", updateTotal);
    });
}

// Gesamtsumme aktualisieren
function updateTotal() {
    let total = 0;
    document.querySelectorAll("#kostenTable tbody tr").forEach(row => {
        const menge      = parseFloat(row.querySelector(".menge").value) || 0;
        const preis      = parseFloat(row.querySelector(".preis").value) || 0;
        const steuerSatz = parseFloat(row.querySelector(".mwst").value) || 0;

        const zeilenGesamt = menge * preis;
        const steuer       = (zeilenGesamt * steuerSatz) / 100;
        const gesamt       = zeilenGesamt + steuer;

        row.querySelector(".gesamtpreis").textContent = gesamt.toFixed(2) + " €";
        total += gesamt;
    });

    const rabattProzent = parseFloat(document.getElementById("rabatt_prozent").value) || 0;
    total -= (total * rabattProzent) / 100;

    const anzahlung = parseFloat(document.getElementById("anzahlung").value) || 0;
    total -= anzahlung;

    document.getElementById("gesamtbetrag").textContent = total.toFixed(2);
    document.getElementById('preis_gesamt_input').value = total.toFixed(2);
}

// ----------------------------------------------------------------------------
// Live-PDF-Vorschau (sofern admin.auftraege.preview existiert)
// ----------------------------------------------------------------------------
function initPDFPreview() {
    const auftragForm = document.getElementById("auftragForm");
    const pdfPreview  = document.getElementById("pdfPreview");

    if (!auftragForm || !pdfPreview) return;

    async function updatePDFPreview() {
        const formData = new FormData(auftragForm);

        try {
            const response = await fetch(auftragForm.dataset.previewUrl, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": auftragForm.dataset.csrfToken
                }
            });
            if (!response.ok) throw new Error("Fehler beim Laden der PDF-Vorschau");

            const blob = await response.blob();
            pdfPreview.src = URL.createObjectURL(blob);
        } catch (error) {
            console.error(error);
        }
    }

    // Bei jeder Eingabe (input) generieren wir eine neue Preview
    auftragForm.addEventListener("input", updatePDFPreview);
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection
