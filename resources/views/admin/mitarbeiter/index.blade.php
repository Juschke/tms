@extends('admin.layout')

@section('content')
    <h1>Mitarbeiterübersicht</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.mitarbeiter.create') }}" class="btn btn-primary mb-3">Neuen Mitarbeiter anlegen</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>E-Mail</th>
                <th>Erstellt am</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mitarbeiter as $m)
                <tr>
                    <td>{{ $m->id }}</td>
                    <td>{{ $m->vorname }}</td>
                    <td>{{ $m->nachname }}</td>
                    <td>{{ $m->email }}</td>
                    <td>{{ $m->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.mitarbeiter.show', $m->id) }}" class="btn btn-info btn-sm">Anzeigen</a>
                        <a href="{{ route('admin.mitarbeiter.edit', $m->id) }}" class="btn btn-warning btn-sm">Bearbeiten</a>
                        <form action="{{ route('admin.mitarbeiter.destroy', $m->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Mitarbeiter wirklich löschen?')">Löschen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
