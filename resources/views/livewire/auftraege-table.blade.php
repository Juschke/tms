<div class="card shadow border-0">
    <!-- Card Header mit Suchfeld & Neu-Button -->
    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-list-task me-2"></i> Übersetzungsaufträge
        </h5>

        <div class="d-flex gap-2 align-items-center">
            <!-- Suchfeld minimalistisch -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       wire:model.debounce.300ms="search"
                       class="form-control border-0"
                       placeholder="Auftrag suchen..."
                       style="box-shadow: none;">
            </div>

            <!-- Neu-Button mit Icon -->
            <a href="{{ route('admin.auftraege.create') }}" class="btn btn-light btn-sm text-primary d-flex align-items-center">
                <i class="bi bi-plus-circle me-1"></i>
                Neu
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if (session()->has('message'))
            <div class="alert alert-success m-3">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter Bar: Status + Sortierung -->
        <div class="bg-light p-2 d-flex flex-wrap gap-2 align-items-center border-bottom">
            <!-- Status-Filter -->
            <div class="btn-group btn-group-sm" role="group">
                <button type="button"
                        wire:click="filterByStatus('')"
                        class="btn {{ $statusFilter == '' ? 'btn-primary' : 'btn-outline-primary' }} px-3">
                    <i class="bi bi-collection me-1"></i> Alle
                </button>
                <button type="button"
                        wire:click="filterByStatus('Neu')"
                        class="btn {{ $statusFilter == 'Neu' ? 'btn-primary' : 'btn-outline-primary' }} px-3">
                    <i class="bi bi-star me-1"></i> Neu
                </button>
                <button type="button"
                        wire:click="filterByStatus('InBearbeitung')"
                        class="btn {{ $statusFilter == 'InBearbeitung' ? 'btn-primary' : 'btn-outline-primary' }} px-3">
                    <i class="bi bi-tools me-1"></i> In Bearbeitung
                </button>
                <button type="button"
                        wire:click="filterByStatus('Abgeschlossen')"
                        class="btn {{ $statusFilter == 'Abgeschlossen' ? 'btn-primary' : 'btn-outline-primary' }} px-3">
                    <i class="bi bi-check2-circle me-1"></i> Abgeschlossen
                </button>
            </div>

            <!-- Sortierung -->
            <div class="dropdown ms-auto">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1"
                        type="button" id="sortDropdown"
                        data-bs-toggle="dropdown">
                    <i class="bi bi-sort-down"></i> Sortieren
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1"
                           href="#" wire:click="sortBy('created_at')">
                            <i class="bi bi-calendar"></i> Datum
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1"
                           href="#" wire:click="sortBy('prioritaet')">
                            <i class="bi bi-exclamation-triangle"></i> Priorität
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-1"
                           href="#" wire:click="sortBy('auftragsnummer')">
                            <i class="bi bi-hash"></i> Auftragsnummer
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tabelle -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="90">Aktionen</th>
                        <th width="130">Status / Priorität</th>
                        <th>Auftragsinformationen</th>
                        <th width="180">Sprachen</th>
                        <th width="180">Kunde</th>
                        <th width="200">Termin & Notizen</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($auftraege as $auftrag)
                    @php
                        // Optional: Dynamische Rahmenfarbe nur, wenn Priorität = hoch
                        $highlightClass = $auftrag->prioritaet == 'hoch'
                            ? 'border-start border-5 border-danger'
                            : '';
                    @endphp
                    <tr class="{{ $highlightClass }}">
                        <!-- Aktionen -->
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Edit-Button -->
                                <a href="{{ route('admin.auftraege.edit', $auftrag->id) }}"
                                   class="btn btn-primary text-white"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Bearbeiten">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <!-- Show-Button -->
                                <a href="{{ route('admin.auftraege.show', $auftrag->id) }}"
                                   class="btn btn-info text-white"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <!-- Delete-Button -->
                                <button class="btn btn-danger text-white"
                                        wire:click="deleteSingle({{ $auftrag->id }})"
                                        onclick="confirm('Diesen Auftrag wirklich löschen?') || event.stopImmediatePropagation()"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Löschen">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Status & Priorität -->
                        <td>
                            @php
                                $statusColors = [
                                    'Neu' => 'bg-primary text-white',
                                    'InBearbeitung' => 'bg-warning text-white',
                                    'Abgeschlossen' => 'bg-success text-white',
                                    'Storniert' => 'bg-danger text-white',
                                ];
                                
                                $priorityColors = [
                                    'niedrig' => 'bg-success text-white',
                                    'normal' => 'bg-warning text-white',
                                    'hoch' => 'bg-danger text-white',
                                    'dringend' => 'bg-secondary text-white'
                                ];
                                
                                $statusColor = $statusColors[$auftrag->status] ?? 'bg-secondary';
                                $priorityColor = $priorityColors[strtolower($auftrag->prioritaet)] ?? 'bg-secondary';
                            @endphp

                            <!-- Status Dropdown -->
                            <div class="dropdown mb-1">
                                <button class="btn btn-sm w-100 dropdown-toggle text-truncate {{ $statusColor }}"
                                        type="button"
                                        id="statusDropdown{{ $auftrag->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="white-space: nowrap;">
                                    {{ ucfirst($auftrag->status) }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $auftrag->id }}">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updateStatus({{ $auftrag->id }}, 'Neu')">
                                           Neu
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updateStatus({{ $auftrag->id }}, 'InBearbeitung')">
                                           In Bearbeitung
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updateStatus({{ $auftrag->id }}, 'Abgeschlossen')">
                                           Abgeschlossen
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updateStatus({{ $auftrag->id }}, 'Storniert')">
                                           Storniert
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Priorität Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm w-100 dropdown-toggle text-truncate {{ $priorityColor }}"
                                        type="button"
                                        id="priorityDropdown{{ $auftrag->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="white-space: nowrap;">
                                    {{ ucfirst($auftrag->prioritaet) }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="priorityDropdown{{ $auftrag->id }}">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updatePriority({{ $auftrag->id }}, 'niedrig')">
                                           Niedrig
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updatePriority({{ $auftrag->id }}, 'normal')">
                                           Normal
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updatePriority({{ $auftrag->id }}, 'hoch')">
                                           Hoch
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click="updatePriority({{ $auftrag->id }}, 'dringend')">
                                           Dringend
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>

                        <!-- Auftragsinformationen -->
                        <td>
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">{{ $auftrag->auftragsnummer }}</div>
                                    <div>{{ $auftrag->titel ?? 'Kein Titel' }}</div>
                                    <div class="text-muted small mt-1">
                                        <span class="me-2">
                                            <i class="bi bi-person"></i>
                                            {{ optional($auftrag->benutzer)->name ?? 'Nicht zugewiesen' }}
                                        </span>
                                        <span>
                                            <i class="bi bi-calendar-event"></i>
                                            {{ optional($auftrag->created_at)->format('d.m.Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Angerufen Checkbox -->
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           wire:model="angerufen.{{ $auftrag->id }}"
                                           id="angerufen{{ $auftrag->id }}">
                                    <label class="form-check-label small text-nowrap" for="angerufen{{ $auftrag->id }}">
                                        Angerufen
                                    </label>
                                </div>
                            </div>
                        </td>

                        <!-- Sprachen -->
                        <td>
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-info text-dark me-2">Quelle</span>
                                <span>{{ $auftrag->quell_sprache ?? '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">Ziel</span>
                                <span>{{ $auftrag->ziel_sprache ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Kunde -->
                        <td>
                            <a href="{{ route('admin.kunden.show', optional($auftrag->kunde)->id) }}"
                               class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 text-primary">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <strong>{{ optional($auftrag->kunde)->firmenname ?? '-' }}</strong>
                                        <div class="text-muted small">
                                            {{ optional($auftrag->kunde)->adresse ?? 'Keine Adresse' }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </td>

                        <!-- Termin & Notizen -->
<td>
    @if($auftrag->fällig_am)
        <div class="mb-2 d-flex align-items-center">
            <i class="bi bi-alarm me-1
               {{ strtotime($auftrag->fällig_am) < time() ? 'text-danger' : 'text-success' }}">
            </i>
            <span class="{{ strtotime($auftrag->fällig_am) < time() ? 'text-danger fw-bold' : '' }}">
                {{ date('d.m.Y', strtotime($auftrag->fällig_am)) }}
            </span>
        </div>
    @endif

    <!-- Datum-Icon ähnlich deinem Beispiel -->
    <div class="mb-2 d-flex align-items-center text-muted small">
        <i class="bi bi-calendar-event me-1"></i>
        <i class="bi bi-phone me-1"></i>
        <span>{{ optional($auftrag->created_at)->format('d.m.Y') }}</span>
    </div>

    <div class="input-group input-group-sm">
        <!-- Statt <input type="text"> jetzt ein <textarea> -->
        <textarea
            class="form-control"
            rows="1"
            wire:model.lazy="anmerkungen.{{ $auftrag->id }}"
            placeholder="Notiz..."
        ></textarea>
        <button
            class="btn btn-primary text-white"
            type="button"
            wire:click="saveNote({{ $auftrag->id }})"
            data-bs-toggle="tooltip"
            title="Speichern"
        >
            <i class="bi bi-pencil"></i>
        </button>
    </div>
</td>
                    </tr>
                @empty
                    <!-- Keine Aufträge gefunden -->
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                <p class="mb-0">Keine Aufträge gefunden.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginator & perPage -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 small" for="perPageSelect">Zeige:</label>
                <select class="form-select form-select-sm w-auto" wire:model="perPage" id="perPageSelect">
                    <option value="10">10 pro Seite</option>
                    <option value="25">25 pro Seite</option>
                    <option value="50">50 pro Seite</option>
                </select>
            </div>
            
            <div>
                {{ $auftraege->links() }}
            </div>
        </div>
    </div>
</div>
