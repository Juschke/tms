
@extends('admin.layout')

@section('content')
    <h1>Kunde bearbeiten</h1>

    <form action="{{ route('admin.kunden.update', $kunde->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="firmenname" class="form-label">Firmenname</label>
            <input type="text" name="firmenname" id="firmenname" class="form-control" 
                   value="{{ old('firmenname', $kunde->firmenname) }}" required>
        </div>
        <div class="mb-3">
            <label for="kontakt_email" class="form-label">Kontakt-E-Mail</label>
            <input type="email" name="kontakt_email" id="kontakt_email" class="form-control" 
                   value="{{ old('kontakt_email', $kunde->kontakt_email) }}" required>
        </div>
        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" name="telefon" id="telefon" class="form-control" 
                   value="{{ old('telefon', $kunde->telefon) }}">
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" class="form-control">{{ old('adresse', $kunde->adresse) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="unternehmensname" class="form-label">Unternehmensname</label>
            <input type="text" name="unternehmensname" id="unternehmensname" class="form-control" 
                   value="{{ old('unternehmensname', $kunde->unternehmensname) }}">
        </div>

        <button type="submit" class="btn btn-primary">Aktualisieren</button>
        <a href="{{ route('admin.kunden.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
