@extends('admin.layout')

@section('content')
    <h1>Partner bearbeiten</h1>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.partner.update', $partner->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name des Partners</label>
            <input type="text" name="name" id="name" 
                   class="form-control" 
                   value="{{ old('name', $partner->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" name="email" id="email" 
                   class="form-control" 
                   value="{{ old('email', $partner->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rolle</label>
            <select name="role" id="role" class="form-select">
                <option value="">- bitte wählen -</option>
                <option value="Übersetzer" 
                    {{ old('role', $partner->role) === 'Übersetzer' ? 'selected' : '' }}>
                    Übersetzer
                </option>
                <option value="Dolmetscher" 
                    {{ old('role', $partner->role) === 'Dolmetscher' ? 'selected' : '' }}>
                    Dolmetscher
                </option>
                <option value="Übersetzer & Dolmetscher" 
                    {{ old('role', $partner->role) === 'Übersetzer & Dolmetscher' ? 'selected' : '' }}>
                    Übersetzer & Dolmetscher
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" name="telefon" id="telefon" 
                   class="form-control" 
                   value="{{ old('telefon', $partner->telefon) }}">
        </div>

        <div class="mb-3">
            <label for="kommentar" class="form-label">Kommentar</label>
            <textarea name="kommentar" id="kommentar" class="form-control" rows="3">
                {{ old('kommentar', $partner->kommentar) }}
            </textarea>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
        <a href="{{ route('admin.partner.index') }}" class="btn btn-secondary">Abbrechen</a>
    </form>
@endsection
