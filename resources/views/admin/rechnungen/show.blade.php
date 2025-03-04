
@extends('admin.layout')

@section('content')
    <h1>Rechnung anzeigen</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $rechnung->id }}
    </div>
    <div class="mb-3">
        <strong>Rechnungsnummer:</strong> {{ $rechnung->rechnungsnummer }}
    </div>
    <div class="mb-3">
        <strong>Auftrag:</strong>
        @if($rechnung->auftrag)
            #{{ $rechnung->auftrag->auftragsnummer }} - {{ $rechnung->auftrag->titel }}
        @else
            <em>- kein Auftrag -</em>
        @endif
    </div>
    <div class="mb-3">
        <strong>Kunde:</strong>
        @if($rechnung->kunde)
            {{ $rechnung->kunde->firmenname }}
        @else
            <em>- kein Kunde -</em>
        @endif
    </div>
    <div class="mb-3">
        <strong>Summe gesamt:</strong> {{ $rechnung->summe_gesamt }}
    </div>
    <div class="mb-3">
        <strong>Steuersumme:</strong> {{ $rechnung->steuersumme }}
    </div>
    <div class="mb-3">
        <strong>Endbetrag:</strong> {{ $rechnung->endbetrag }}
    </div>
    <div class="mb-3">
        <strong>PDF-Pfad:</strong> {{ $rechnung->pdf_pfad }}
    </div>
    <div class="mb-3">
        <strong>Ausgestellt am:</strong> {{ $rechnung->ausgestellt_am }}
    </div>
    <div class="mb-3">
        <strong>Fällig am:</strong> {{ $rechnung->faellig_am }}
    </div>
    <div class="mb-3">
        <strong>Status:</strong> {{ $rechnung->status }}
    </div>
    <div class="mb-3">
        <strong>Erstellt am:</strong> {{ $rechnung->created_at }}
    </div>

    <a href="{{ route('admin.rechnungen.index') }}" class="btn btn-secondary">Zurück</a>
    <a href="{{ route('admin.rechnungen.edit', $rechnung->id) }}" class="btn btn-primary">Bearbeiten</a>
@endsection
