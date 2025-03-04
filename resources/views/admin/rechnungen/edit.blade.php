
@extends('admin.layout')

@section('content')
    <h1>Rechnung bearbeiten</h1>

    <form action="{{ route('admin.rechnungen.update', $rechnung->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="rechnungsnummer" class="form-label">Rechnungsnummer</label>
            <input type="text" name="rechnungsnummer" id="rechnungsnummer" class="form-control" 
                   value="{{ old('rechnungsnummer', $rechnung->rechnungsnummer) }}" required>
        </div>

        <div class="mb-3">
            <label for="auftrag_id" class="form-label">Auftrag</label>
            <select name="auftrag_id" id="auftrag_id" class="form-select" required>
                <option value="">- bitte wählen -</option>
                @foreach($auftraege as $auftrag)
                    <option value="{{ $auftrag->id }}" 
                        {{ (old('auftrag_id', $rechnung->auftrag_id) == $auftrag->id) ? 'selected' : '' }}>
                        #{{ $auftrag->auftragsnummer }} - {{ $auftrag->titel }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="kunde_id" class="form-label">Kunde</label>
            <select name="kunde_id" id="kunde_id" class="form-select" required>
                <option value="">- bitte wählen -</option>
                @foreach($kunden as $kunde)
                    <option value="{{ $kunde->id }}"
                        {{ (old('kunde_id', $rechnung->kunde_id) == $kunde->id) ? 'selected' : '' }}>
                        {{ $kunde->firmenname }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="summe_gesamt" class="form-label">Summe gesamt</label>
            <input type="number" step="0.01" name="summe_gesamt" id="summe_gesamt" class="form-control"
                   value="{{ old('summe_gesamt', $rechnung->summe_gesamt) }}">
        </div>

        <div class="mb-3">
            <label for="steuersumme" class="form-label">Steuersumme</label>
            <input type="number" step="0.01" name="steuersumme" id="steuersumme" class="form-control"
                   value="{{ old('steuersumme', $rechnung->steuersumme) }}">
        </div>

        <div class="mb-3">
            <label for="endbetrag" class="form-label">Endbetrag</label>
            <input type="number" step="0.01" name="endbetrag" id="endbetrag" class="form-control"
                   value="{{ old('endbetrag', $rechnung->endbetrag) }}">
        </div>

        <div class="mb-3">
            <label for="pdf_pfad" class="form-label">Pfad zur PDF-Datei</label>
            <input type="text" name="pdf_pfad" id="pdf_pfad" class="form-control"
                   value="{{ old('pdf_pfad', $rechnung->pdf_pfad) }}">
        </div>

        <div class="mb-3">
            <label for="ausgestellt_am" class="form-label">Ausgestellt am</label>
            <input type="date" name="ausgestellt_am" id="ausgestellt_am" class="form-control"
                   value="{{ old('ausgestellt_am', $rechnung->ausgestellt_am) }}">
        </div>

        <div class="mb-3">
            <label for="faellig_am" class="form-label">Fällig am</label>
            <input type="date" name="faellig_am" id="faellig_am" class="form-control"
                   value="{{ old('faellig_am', $rechnung->faellig_am) }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                @foreach(['Offen','Bezahlt','Ueberfaellig'] as $st)
                    <option value="{{ $st }}" 
                        {{ (old('status', $rechnung->status) == $st) ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Aktualisieren</button>
        <a href="{{ route('admin.rechnungen.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
