@extends('admin.layout')

@section('content')
    <h1>Mitarbeiterdetails</h1>

    <h2>{{ $mitarbeiter->vorname }} {{ $mitarbeiter->nachname }}</h2>
    <p><strong>E-Mail:</strong> {{ $mitarbeiter->email }}</p>
    <p><strong>Telefon:</strong> {{ $mitarbeiter->telefon }}</p>
    <p><strong>Position:</strong> {{ $mitarbeiter->position }}</p>

    <a href="{{ route('admin.mitarbeiter.index') }}" class="btn btn-secondary">Zurück</a>
@endsection
