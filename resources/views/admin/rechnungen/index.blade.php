@extends('admin.layout')

@section('content')
<h1>Rechnungsübersicht</h1>

<a href="{{ route('admin.rechnungen.create') }}" class="btn btn-primary mb-3">Neue Rechnung anlegen</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Rechnungsnummer</th>
            <th>Auftrag</th>
            <th>Kunde</th>
            <th>Status</th>
            <th>Erstellt am</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rechnungen as $rechnung)
        <tr>
            <td>{{ $rechnung->id }}</td>
            <td>{{ $rechnung->rechnungsnummer }}</td>
            <td>
                @if($rechnung->auftrag)
                    #{{ $rechnung->auftrag->auftragsnummer }} - {{ $rechnung->auftrag->titel }}
                @else
                    <em>- kein Auftrag -</em>
                @endif
            </td>
            <td>
                @if($rechnung->kunde)
                    {{ $rechnung->kunde->firmenname }}
                @else
                    <em>- kein Kunde -</em>
                @endif
            </td>
            <td>{{ $rechnung->status }}</td>
            <td>{{ $rechnung->created_at }}</td>
            <td>
                <a href="{{ route('admin.rechnungen.show', $rechnung->id) }}" class="btn btn-info btn-sm">Anzeigen</a>
                <a href="{{ route('admin.rechnungen.edit', $rechnung->id) }}" class="btn btn-warning btn-sm">Bearbeiten</a>
                <form action="{{ route('admin.rechnungen.destroy', $rechnung->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Diese Rechnung wirklich löschen?')">
                        Löschen
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
