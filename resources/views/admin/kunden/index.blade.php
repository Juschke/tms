@extends('admin.layout')

@section('content')
<h1>Kundenübersicht</h1>

<a href="{{ route('admin.kunden.create') }}" class="btn btn-primary mb-3">Neuen Kunden anlegen</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Firmenname</th>
            <th>Kontakt E-Mail</th>
            <th>Erstellt am</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kunden as $kunde)
        <tr>
            <td>{{ $kunde->id }}</td>
            <td>{{ $kunde->firmenname }}</td>
            <td>{{ $kunde->kontakt_email }}</td>
            <td>{{ $kunde->created_at }}</td>
            <td>
                <a href="{{ route('admin.kunden.show', $kunde->id) }}" class="btn btn-info btn-sm">Anzeigen</a>
                <a href="{{ route('admin.kunden.edit', $kunde->id) }}" class="btn btn-warning btn-sm">Bearbeiten</a>
                <form action="{{ route('admin.kunden.destroy', $kunde->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm" onclick="return confirm('Kunden wirklich löschen?')">Löschen</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
