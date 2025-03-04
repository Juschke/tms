@extends('admin.layout') 

@section('content')
    <h1>Einstellungen Übersicht</h1>

    {{-- Flash-Meldung, falls du .with('success', '...') verwendest --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p>Hier könntest du allgemeine Einstellungen anzeigen.</p>

    <a href="{{ route('admin.einstellungen.create') }}" class="btn btn-primary">
        Neue Einstellung anlegen
    </a>
@endsection
