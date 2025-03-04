
@extends('admin.layout')

@section('content')
    <h1>Kunde anzeigen</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $kunde->id }}
    </div>
    <div class="mb-3">
        <strong>Firmenname:</strong> {{ $kunde->firmenname }}
    </div>
    <div class="mb-3">
        <strong>Kontakt E-Mail:</strong> {{ $kunde->kontakt_email }}
    </div>
    <div class="mb-3">
        <strong>Telefon:</strong> {{ $kunde->telefon }}
    </div>
    <div class="mb-3">
        <strong>Adresse:</strong> {{ $kunde->adresse }}
    </div>
    <div class="mb-3">
        <strong>Unternehmensname:</strong> {{ $kunde->unternehmensname }}
    </div>
    <div class="mb-3">
        <strong>Erstellt am:</strong> {{ $kunde->created_at }}
    </div>

    <a href="{{ route('admin.kunden.index') }}" class="btn btn-secondary">Zurück</a>
    <a href="{{ route('admin.kunden.edit', $kunde->id) }}" class="btn btn-primary">Bearbeiten</a>
@endsection
