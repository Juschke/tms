@extends('admin.layout')

@section('content')
<div class="container-fluid h-100">

    {{-- Title / Header --}}
    <div class="sap-topnav sticky-top bg-white border-bottom mb-3 py-2" style="z-index:1000;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="sap-title mb-0">Neuen Auftrag anlegen</h1>
            <div>
                <a href="{{ route('admin.auftraege.index') }}" class="sap-btn-ghost me-2">
                    <i class="bi bi-x-lg"></i> Abbrechen
                </a>
                {{-- Only show the "Speichern" button on the final step --}}
                <button type="submit" form="auftragForm" id="finalSubmitBtn" class="sap-btn d-none">
                    <i class="bi bi-check-lg"></i> Speichern
                </button>
            </div>
        </div>
    </div>
{{-- HORIZONTALE STEP-BAR (mit Icons) --}}
<div class="sap-stepper d-flex justify-content-between align-items-center mb-4">
    <!-- Schritt 1 -->
    <div class="step" data-step="1">
        <div class="icon" id="stepIcon1">
            <i class="bi bi-file-text"></i>
        </div>
        <div class="label">Auftragsdaten</div>
    </div>
    <div class="divider" id="divider1"></div>
    <!-- Schritt 2 -->
    <div class="step" data-step="2">
        <div class="icon" id="stepIcon2">
            <i class="bi bi-translate"></i>
        </div>
        <div class="label">Sprachen & Dokumente</div>
    </div>
    <div class="divider" id="divider2"></div>
    <!-- Schritt 3 -->
    <div class="step" data-step="3">
        <div class="icon" id="stepIcon3">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="label">Preiskalkulation</div>
    </div>
    <div class="divider" id="divider3"></div>
    <!-- Schritt 4 -->
    <div class="step" data-step="4">
        <div class="icon" id="stepIcon4">
            <i class="bi bi-check2-circle"></i>
        </div>
        <div class="label">Abschluss</div>
    </div>
</div>


    {{-- Multi-Step Form --}}
    <form
        id="auftragForm"
        action="{{ route('admin.auftraege.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <!-- ==========================
             STEP 1: Erste 3 "Cards"
        =========================== -->
        <div class="step-container" data-step="1">
            <div class="sap-card mb-3">
                <h2 class="sap-card-title"><i class="bi bi-file-text"></i> Auftragsdaten</h2>
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <div class="sap-form-group">
                            <label for="titel" class="sap-label required">Titel</label>
                            <div class="title-badges">
                                <span class="badge badge-primary" onclick="setTitle('Geburturkunde')">Geburturkunde</span>
                                <span class="badge badge-secondary" onclick="setTitle('Heiratsurkunde')">Heiratsurkunde</span>
                                <span class="badge badge-success" onclick="setTitle('Beglaubigte Übersetzung')">
                                    Beglaubigte Übersetzung von {{ old('quell_sprache') }} in {{ old('ziel_sprache') }}
                                </span>
                                <span class="badge badge-warning" onclick="setTitle('Beglaubigt + Apostillebescheid')">
                                    Beglaubigt + Apostillebescheid
                                </span>
                            </div>
                            <input
                                type="text"
                                id="titel"
                                name="titel"
                                class="sap-form-control @error('titel') is-invalid @enderror"
                                value="{{ old('titel') }}"
                                required
                            >
                            @error('titel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="sap-form-group">
                            <label for="erstellungsdatum" class="sap-label">Erstellungsdatum</label>
                            <input
                                type="datetime-local"
                                id="erstellungsdatum"
                                name="erstellungsdatum"
                                class="sap-form-control @error('erstellungsdatum') is-invalid @enderror"
                                value="{{ old('erstellungsdatum', date('Y-m-d\TH:i')) }}"
                                readonly
                            >
                            @error('erstellungsdatum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="sap-form-group">
                            <label for="faellig_am" class="sap-label">Fälligkeitsdatum</label>
                            <input
                                type="date"
                                id="faellig_am"
                                name="faellig_am"
                                class="sap-form-control @error('faellig_am') is-invalid @enderror"
                                value="{{ old('faellig_am') }}"
                                
                            >
                            @error('faellig_am')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendarModal">
                            Füge Termin hinzu
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                {{-- Card: Kunde & Zuständigkeiten --}}
                <div class="col-md-6">
                    <div class="sap-card h-100">
                        <h2 class="sap-card-title"><i class="bi bi-people"></i> Kunde & Zuständigkeiten</h2>
                        <div class="sap-form-group">
                            <label for="kunde_id" class="sap-label required">Kunde</label>
                            <select
                                id="kunde_id"
                                name="kunde_id"
                                class="sap-form-control @error('kunde_id') is-invalid @enderror"
                                required
                            >
                                <option value="">- Bitte wählen -</option>
                                @foreach($kunden as $kunde)
                                    <option
                                        value="{{ $kunde->id }}"
                                        {{ old('kunde_id') == $kunde->id ? 'selected' : '' }}
                                    >
                                        {{ $kunde->firmenname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kunde_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sap-form-group">
                            <label for="mitarbeiter" class="sap-label">Zuständiger Mitarbeiter</label>
                            <select id="mitarbeiter" name="mitarbeiter" class="sap-form-control">
                                <option value="{{ auth()->user()->id ?? '' }}" selected>
                                    {{ auth()->user()->name ?? 'Mitarbeiter auswählen' }}
                                </option>
                            </select>
                        </div>

                        <div class="sap-form-group">
                            <label for="partner_id" class="sap-label">Externer Partner</label>
                            <select
                                id="partner_id"
                                name="partner_id"
                                class="sap-form-control"
                            >
                                <option value="">- Bitte wählen -</option>
                                @foreach($partner as $p)
                                    <option
                                        value="{{ $p->id }}"
                                        {{ old('partner_id') == $p->id ? 'selected' : '' }}
                                    >
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sap-form-group mt-3">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="angerufen"
                                    name="angerufen"
                                    value="1"
                                    {{ old('angerufen') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="angerufen">Kunde kontaktiert</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Status & Priorität --}}
                <div class="col-md-6">
                    <div class="sap-card h-100">
                        <h2 class="sap-card-title"><i class="bi bi-card-checklist"></i> Status & Priorität</h2>
                        <div class="sap-form-group">
                            <label for="status" class="sap-label required">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="sap-form-control @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="Neu" {{ old('status') == 'Neu' ? 'selected' : '' }}>Neu</option>
                                <option value="InBearbeitung" {{ old('status') == 'InBearbeitung' ? 'selected' : '' }}>
                                    In Bearbeitung
                                </option>
                                <option value="Abgeschlossen" {{ old('status') == 'Abgeschlossen' ? 'selected' : '' }}>
                                    Abgeschlossen
                                </option>
                                <option value="Storniert" {{ old('status') == 'Storniert' ? 'selected' : '' }}>
                                    Storniert
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sap-form-group">
                            <label for="prioritaet" class="sap-label required">Priorität</label>
                            <select
                                id="prioritaet"
                                name="prioritaet"
                                class="sap-form-control @error('prioritaet') is-invalid @enderror"
                                required
                            >
                                <option value="">- Bitte wählen -</option>
                                <option value="Niedrig" {{ old('prioritaet') == 'Niedrig' ? 'selected' : '' }}>Niedrig</option>
                                <option value="Normal" {{ old('prioritaet') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="Hoch" {{ old('prioritaet') == 'Hoch' ? 'selected' : '' }}>Hoch</option>
                                <option value="Dringend" {{ old('prioritaet') == 'Dringend' ? 'selected' : '' }}>Dringend</option>
                            </select>
                            @error('prioritaet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 1 Buttons --}}
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="sap-btn" onclick="goToStep(2)">
                    Weiter <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        <!-- END STEP 1 -->

        <!-- ==========================
             STEP 2: Sprachen & Dokumente
        =========================== -->
        <div class="step-container d-none" data-step="2">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="sap-card h-100">
                        <h2 class="sap-card-title"><i class="bi bi-translate"></i> Sprachen</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sap-form-group">
                                    <label for="quell_sprache" class="sap-label required">Quellsprache</label>
                                    <select
                                        id="quell_sprache"
                                        name="quell_sprache"
                                        class="sap-form-control @error('quell_sprache') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">- Bitte wählen -</option>
                                        @foreach($sprachen as $sprache)
                                            <option
                                                value="{{ $sprache->id }}"
                                                {{ old('quell_sprache') == $sprache->id ? 'selected' : '' }}
                                            >
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
                                    <select
                                        id="ziel_sprache"
                                        name="ziel_sprache"
                                        class="sap-form-control @error('ziel_sprache') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">- Bitte wählen -</option>
                                        @foreach($sprachen as $sprache)
                                            <option
                                                value="{{ $sprache->id }}"
                                                {{ old('ziel_sprache') == $sprache->id ? 'selected' : '' }}
                                            >
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

                        {{-- Weitere Sprachen (optional, Mehrfachauswahl) --}}
                        <div class="mt-3">
                            <button type="button" class="sap-btn" id="openSprachenModal">
                                <i class="bi bi-plus-circle"></i> Weitere Sprachen hinzufügen
                            </button>
                            <div class="mt-2" id="selectedSprachenContainer">
                                {{-- Alte Werte wiederherstellen --}}
                                @if(old('sprachen'))
                                    @foreach(old('sprachen') as $spId)
                                        @php
                                            $spracheObj = $sprachen->firstWhere('id', $spId);
                                        @endphp
                                        @if($spracheObj)
                                            <div class="p-1 border rounded d-inline-block me-1 mb-1">
                                                {{ $spracheObj->bez_lang }} ({{ $spracheObj->bez_kurz }})
                                                <input type="hidden" name="sprachen[]" value="{{ $spId }}">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dokumente --}}
                <div class="col-md-6">
                    <div class="sap-card h-100">
                        <h2 class="sap-card-title"><i class="bi bi-upload"></i> Dokumente</h2>
                        <div class="sap-form-group">
                            <label for="hochgeladene_datei" class="sap-label">Datei hochladen</label>
                            <input
                                type="file"
                                id="hochgeladene_datei"
                                name="hochgeladene_datei"
                                class="sap-form-control"
                            >
                            <small class="text-muted d-block mt-2">
                                PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (max. 10MB)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2 Buttons --}}
            <div class="d-flex justify-content-between">
                <button type="button" class="sap-btn-ghost" onclick="goToStep(1)">
                    <i class="bi bi-arrow-left"></i> Zurück
                </button>
                <button type="button" class="sap-btn" onclick="goToStep(3)">
                    Weiter <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        <!-- END STEP 2 -->

        <!-- ==========================
             STEP 3: Preiskalkulation
        =========================== -->
        <div class="step-container d-none" data-step="3">
            <div class="sap-card mb-3">
                <h2 class="sap-card-title"><i class="bi bi-cash-stack"></i> Preiskalkulation</h2>
                <div class="table-responsive">
                    <table id="kostenTable" class="table">
                        <thead>
                            <tr>
                                <th>Beschreibung</th>
                                <th>Menge</th>
                                <th>Einheit</th>
                                <th>Preis (€)</th>
                                <th>Steuer (%)</th>
                                <th>Gesamt (€)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Dynamische Positionen werden per JS eingefügt --}}
                        </tbody>
                    </table>
                </div>
                <button type="button" class="sap-btn mt-2" id="addPositionBtn">
                    <i class="bi bi-plus-circle"></i> Position hinzufügen
                </button>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="sap-form-group">
                            <label for="rabatt_prozent" class="sap-label">Rabatt (%)</label>
                            <input
                                type="number"
                                step="0.01"
                                id="rabatt_prozent"
                                name="rabatt_prozent"
                                class="sap-form-control"
                                value="{{ old('rabatt_prozent') }}"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sap-form-group">
                            <label for="anzahlung" class="sap-label">Anzahlung (€)</label>
                            <input
                                type="number"
                                step="0.01"
                                id="anzahlung"
                                name="anzahlung"
                                class="sap-form-control"
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
                <input type="hidden" name="preis_gesamt" id="preis_gesamt_input">
            </div>

            {{-- Step 3 Buttons --}}
            <div class="d-flex justify-content-between">
                <button type="button" class="sap-btn-ghost" onclick="goToStep(2)">
                    <i class="bi bi-arrow-left"></i> Zurück
                </button>
                <button type="button" class="sap-btn" onclick="goToStep(4)">
                    Weiter <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        <!-- END STEP 3 -->

        <!-- ==========================
             STEP 4: Anmerkungen + Abschließen
        =========================== -->
        <div class="step-container d-none" data-step="4">
            <div class="sap-card mb-3">
                <h2 class="sap-card-title">Anmerkungen</h2>
                <div class="sap-form-group">
                    <textarea
                        id="anmerkungen"
                        name="beschreibung"
                        rows="3"
                        class="sap-form-control @error('beschreibung') is-invalid @enderror"
                    >{{ old('beschreibung') }}</textarea>
                    @error('beschreibung')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- You could optionally include a "summary" of all data here, or any final confirmation text. --}}

            {{-- Abschluss-Buttons --}}
            <div class="d-flex justify-content-between mb-5">
                <button type="button" class="sap-btn-ghost" onclick="goToStep(3)">
                    <i class="bi bi-arrow-left"></i> Zurück
                </button>
                <button type="submit" class="sap-btn" id="finalStepSubmit">
                    <i class="bi bi-check-lg"></i> Auftrag speichern
                </button>
            </div>
        </div>
        <!-- END STEP 4 -->

    </form>
</div>

<!-- ==========================
     MODAL: Kalender
=========================== -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Termin auswählen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="calendar-container">
                    <button class="btn btn-light" onclick="changeYear(-1)">&lt;&lt;</button>
                    <button class="btn btn-light" onclick="changeMonth(-1)">&lt;</button>
                    <span id="currentMonth"></span>
                    <button class="btn btn-light" onclick="changeMonth(1)">&gt;</button>
                    <button class="btn btn-light" onclick="changeYear(1)">&gt;&gt;</button>
                </div>
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Mo</th>
                            <th>Di</th>
                            <th>Mi</th>
                            <th>Do</th>
                            <th>Fr</th>
                            <th>Sa</th>
                            <th>So</th>
                        </tr>
                    </thead>
                    <tbody id="monthView"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                <button type="button" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================
     MODAL: Sprachen
=========================== -->
<div class="sap-modal" id="sprachenModal" style="display:none;">
    <div class="sap-modal-content">
        <div class="sap-modal-header">
            <h3 class="sap-modal-title">Zusätzliche Sprachen auswählen</h3>
            <button type="button" class="sap-modal-close" id="closeSprachenModal">&times;</button>
        </div>

        <div class="sap-form-group mb-3">
            <input type="text" class="sap-form-control" id="sprachenSuche" placeholder="Nach Sprache suchen...">
        </div>

        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sprache</th>
                        <th>Auswahl</th>
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

{{-- Styles für Monatsübersicht & Custom --}}
<style>
    .calendar-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-size: 1.2rem;
    }
    .calendar-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .calendar-table th, .calendar-table td {
        text-align: center;
        padding: 8px;
        border: 1px solid #ddd;
    }
    .calendar-table th {
        background: #f8f9fa;
    }
    .day {
        background: #ffffff;
        cursor: pointer;
        transition: background 0.3s;
    }
    .day:hover {
        background: #007bff;
        color: white;
    }
    .highlight {
        background: rgba(0, 128, 255, 0.3);
    }
    .empty {
        background: #f0f0f0;
    }
    .badge{
        color: rgb(24, 22, 22)!important;
        background-color: lightgrey;
        padding: 0.4rem!important;
        font-size: 10px !important;
        margin: 0.4rem 0.1rem;
        cursor: pointer;
    }
    .badge:hover{
        background-color: #6750A2;
        color: white!important;
    }
    .title-badges{
        display: flex;
        overflow-x: auto;
    }
    /* Hide step containers by default */
    .step-container {
        display: block;
    }
</style>

{{-- JavaScript --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        initSprachenModal();
        initPreiskalkulation();
        renderCalendar();

        // If there are no positions in the table, add one by default
        if (document.querySelector("#kostenTable tbody").children.length === 0) {
            addPosition();
        }
    });

    // ----------------------------------------------------------------------------
    // STEP / WIZARD NAVIGATION
    // ----------------------------------------------------------------------------
    let currentStep = 1;
    const totalSteps = 4;
    const stepProgressBar = document.getElementById('stepProgressBar');
    const stepNumberLabel = document.getElementById('stepNumberLabel');
    const finalSubmitBtn = document.getElementById('finalSubmitBtn'); // if needed
    const finalStepSubmit = document.getElementById('finalStepSubmit');

    function goToStep(stepNumber) {
    // (1) Aktuellen aktiven Step im Stepper deaktivieren
    document.querySelectorAll('.sap-stepper .step').forEach(stepEl => {
        stepEl.classList.remove('active');
    });

    // (2) Neuen Step aktivieren
    const newStepEl = document.querySelector(`.sap-stepper .step[data-step="${stepNumber}"]`);
    if (newStepEl) {
        newStepEl.classList.add('active');
    }

    // (3) Bisherigen Step-Container ausblenden
    const currentStepContainer = document.querySelector(`.step-container[data-step="${currentStep}"]`);
    if (currentStepContainer) {
        currentStepContainer.classList.add('d-none');
    }

    // (4) Ziel-Step-Container einblenden
    const newStepContainer = document.querySelector(`.step-container[data-step="${stepNumber}"]`);
    if (newStepContainer) {
        newStepContainer.classList.remove('d-none');
    }

    // (5) Schritt-Zahl aktualisieren
    currentStep = stepNumber;

    // (6) Progress-Bar updaten (falls du sie weiterhin nutzt)
    const percentage = (currentStep / totalSteps) * 100;
    stepProgressBar.style.width = `${percentage}%`;
    stepProgressBar.setAttribute('aria-valuenow', percentage);
    stepNumberLabel.textContent = currentStep;

    // (7) "Speichern"-Button nur im finalen Schritt
    if (currentStep === totalSteps) {
        finalStepSubmit.style.display = 'inline-block'; 
    } else {
        finalStepSubmit.style.display = 'none';
    }
}


    // ----------------------------------------------------------------------------
    // Title quick-set function
    // ----------------------------------------------------------------------------
    function setTitle(value) {
        document.getElementById('titel').value = value;
    }

    // ----------------------------------------------------------------------------
    // Sprachen-Modal
    // ----------------------------------------------------------------------------
    function initSprachenModal() {
        const sprachenModal = document.getElementById('sprachenModal');
        const openSprachenModalBtn = document.getElementById('openSprachenModal');
        const closeSprachenModalBtn = document.getElementById('closeSprachenModal');
        const uebernehmenSprachenBtn = document.getElementById('uebernehmenSprachenBtn');
        const sprachenSuche = document.getElementById('sprachenSuche');
        const sprachenTableRows = document.querySelectorAll('#sprachenModal .table tbody tr');
        const selectedSprachenContainer = document.getElementById('selectedSprachenContainer');

        if (!sprachenModal) return;

        openSprachenModalBtn?.addEventListener('click', () => {
            sprachenModal.style.display = 'flex';
        });
        closeSprachenModalBtn?.addEventListener('click', () => {
            sprachenModal.style.display = 'none';
        });
        window.addEventListener('click', (e) => {
            if (e.target === sprachenModal) {
                sprachenModal.style.display = 'none';
            }
        });

        sprachenSuche?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            sprachenTableRows.forEach(row => {
                const text = row.cells[0].textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        uebernehmenSprachenBtn?.addEventListener('click', function() {
            // Already-selected IDs (avoid duplicates)
            const alreadySelectedIds = Array.from(
                selectedSprachenContainer.querySelectorAll('input[name="sprachen[]"]')
            ).map(input => input.value);

            document.querySelectorAll('.sprache-checkbox:checked').forEach(cb => {
                if (!alreadySelectedIds.includes(cb.value)) {
                    const chip = document.createElement('div');
                    chip.classList.add('p-1', 'border', 'rounded', 'd-inline-block', 'me-1', 'mb-1');
                    chip.textContent = cb.dataset.name;

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'sprachen[]';
                    hiddenInput.value = cb.value;

                    chip.appendChild(hiddenInput);
                    selectedSprachenContainer.appendChild(chip);
                }
            });
            sprachenModal.style.display = 'none';
        });
    }

    // ----------------------------------------------------------------------------
    // Dynamische Preiskalkulation
    // ----------------------------------------------------------------------------
    function initPreiskalkulation() {
        document.getElementById("rabatt_prozent")?.addEventListener("input", updateTotal);
        document.getElementById("anzahlung")?.addEventListener("input", updateTotal);
        document.getElementById("addPositionBtn")?.addEventListener("click", addPosition);
        attachEventListeners();
    }

    // Neue Position hinzufügen
    function addPosition() {
        const tbody = document.querySelector("#kostenTable tbody");
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>
                <input type="text" name="beschreibung[]" class="sap-form-control" placeholder="z.B. Übersetzung" required>
            </td>
            <td>
                <input type="number" name="menge[]" class="sap-form-control menge" value="1" min="1" required>
            </td>
            <td>
                <select name="einheit[]" class="sap-form-control">
                    <option value="Stück">Stück</option>
                    <option value="Seite">Seite</option>
                    <option value="Zeile">Zeile</option>
                    <option value="Stunde">Stunde</option>
                </select>
            </td>
            <td>
                <input type="number" name="preis[]" class="sap-form-control preis" step="0.01" required>
            </td>
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
        tbody.appendChild(row);
        attachEventListeners();
        updateTotal();
    }

    // Position entfernen
    function removeRow(button) {
        button.closest("tr").remove();
        updateTotal();
    }

    // Event-Listener an Inputs hängen
    function attachEventListeners() {
        document.querySelectorAll(".menge, .preis, .mwst").forEach(input => {
            input.removeEventListener("input", updateTotal);
            input.addEventListener("input", updateTotal);
        });
    }

    // Gesamtsumme berechnen
    function updateTotal() {
        let total = 0;
        document.querySelectorAll("#kostenTable tbody tr").forEach(row => {
            const menge = parseFloat(row.querySelector(".menge").value) || 0;
            const preis = parseFloat(row.querySelector(".preis").value) || 0;
            const steuerSatz = parseFloat(row.querySelector(".mwst").value) || 0;

            const zeilenGesamt = menge * preis;
            const steuer = (zeilenGesamt * steuerSatz) / 100;
            const gesamt = zeilenGesamt + steuer;

            row.querySelector(".gesamtpreis").textContent = gesamt.toFixed(2) + " €";
            total += gesamt;
        });

        const rabattProzent = parseFloat(document.getElementById("rabatt_prozent").value) || 0;
        if (rabattProzent > 0) {
            total -= (total * rabattProzent) / 100;
        }

        const anzahlung = parseFloat(document.getElementById("anzahlung").value) || 0;
        if (anzahlung > 0) {
            total -= anzahlung;
        }

        // Mindestwert 0 (falls zu viel Abzug)
        total = Math.max(total, 0);

        document.getElementById("gesamtbetrag").textContent = total.toFixed(2);
        document.getElementById("preis_gesamt_input").value = total.toFixed(2);
    }

    // ----------------------------------------------------------------------------
    // Calendar
    // ----------------------------------------------------------------------------
    const monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
    let currentDate = new Date();

    function renderCalendar() {
        let year = currentDate.getFullYear();
        let month = currentDate.getMonth();
        document.getElementById("currentMonth").innerText = monthNames[month] + " " + year;
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        let firstDay = new Date(year, month, 1).getDay();
        let today = new Date().getDate();
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let termDate = new Date(year, month, today + 5).getDate();

        let calendarHTML = "";
        let dayCount = 1;
        for (let row = 0; row < 6; row++) {
            let weekHTML = "<tr>";
            for (let col = 0; col < 7; col++) {
                // Der JavaScript-Wochentag 0 = Sonntag -> leichte Anpassung
                // Hier gehen wir davon aus, dass Mo = 1. Rechne an passender Stelle um
                let dayIndex = (firstDay === 0 ? 7 : firstDay); // wenn firstDay=0 => So
                if ((row === 0 && col < (dayIndex - 1)) || dayCount > daysInMonth) {
                    weekHTML += "<td class='empty'></td>";
                } else {
                    let isToday = (dayCount === today && month === currentMonth && year === currentYear) ? 'today' : '';
                    let dayClass = (dayCount >= today && dayCount <= termDate) ? "highlight" : "";
                    weekHTML += `<td class='day ${dayClass} ${isToday}'>${dayCount}</td>`;
                    dayCount++;
                }
            }
            weekHTML += "</tr>";
            calendarHTML += weekHTML;
        }
        document.getElementById("monthView").innerHTML = calendarHTML;

        // Add animation
        const monthView = document.getElementById("monthView");
        monthView.style.opacity = '0';
        monthView.style.transform = 'translateY(10px)';

        setTimeout(() => {
            monthView.style.transition = 'all 0.4s ease';
            monthView.style.opacity = '1';
            monthView.style.transform = 'translateY(0)';
        }, 50);
    }

    function changeMonth(direction) {
        // small animation
        const currentMonthEl = document.getElementById('currentMonth');
        currentMonthEl.style.transition = 'all 0.3s ease';
        currentMonthEl.style.opacity = '0';
        currentMonthEl.style.transform = direction > 0 ? 'translateX(20px)' : 'translateX(-20px)';

        setTimeout(() => {
            currentDate.setMonth(currentDate.getMonth() + direction);
            renderCalendar();

            currentMonthEl.style.transform = direction > 0 ? 'translateX(-20px)' : 'translateX(20px)';
            setTimeout(() => {
                currentMonthEl.style.opacity = '1';
                currentMonthEl.style.transform = 'translateX(0)';
            }, 50);
        }, 200);
    }

    function changeYear(direction) {
        const currentMonthEl = document.getElementById('currentMonth');
        currentMonthEl.style.transition = 'all 0.3s ease';
        currentMonthEl.style.opacity = '0';
        currentMonthEl.style.transform = 'translateY(20px)';

        setTimeout(() => {
            currentDate.setFullYear(currentDate.getFullYear() + direction);
            renderCalendar();

            setTimeout(() => {
                currentMonthEl.style.opacity = '1';
                currentMonthEl.style.transform = 'translateY(0)';
            }, 50);
        }, 200);
    }

    document.querySelectorAll('.sap-stepper .step').forEach(el => 
        el.addEventListener('click', () => (
            document.querySelector('.sap-stepper .step.active')?.classList.remove('active'),
            el.classList.add('active'),
            goToStep(parseInt(el.dataset.step))
  ))
);
</script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection
