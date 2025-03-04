@extends('admin.layout')

@section('content')
    <h1>Partnerübersicht</h1>

    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.partner.create') }}" class="btn btn-primary mb-3">
        Neuen Partner anlegen
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Rolle</th>
                <th>Erstellt am</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partner as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->role }}</td>
                    <td>{{ $p->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.partner.show', $p->id) }}" class="btn btn-info btn-sm">
                            Anzeigen
                        </a>
                        <a href="{{ route('admin.partner.edit', $p->id) }}" class="btn btn-warning btn-sm">
                            Bearbeiten
                        </a>
                        <form action="{{ route('admin.partner.destroy', $p->id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Partner wirklich löschen?')">
                                Löschen
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
