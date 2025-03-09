<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auftrag;
use App\Models\Sprachen;
use Carbon\Carbon;

class AuftraegeTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $allLanguages = [];
    public $selectedLanguageFilters = [];
    public $tempSelectedLanguages = [];
    public $languageSearchTerm = '';

    public $selectAll = false;
    public $selectedItems = [];
    public $perPage = 10;

    public $anmerkungen = [];
    public $angerufen = [];

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        $this->loadAuftragData();
        $this->loadAllLanguages();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Auftrag::query()
            ->with(['kunde', 'benutzer', 'quell_sprache', 'ziel_sprache'])
            ->when($this->search, function($query) {
                $searchTerm = $this->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('auftragsnummer', 'like', '%'.$searchTerm.'%')
                      ->orWhere('titel', 'like', '%'.$searchTerm.'%')
                      ->orWhereHas('kunde', function($subQ) use ($searchTerm) {
                          $subQ->where('firmenname', 'like', '%'.$searchTerm.'%');
                      });
                });
            })
            ->when($this->statusFilter, function($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function($query) {
                return $query->where('prioritaet', $this->priorityFilter);
            })
            ->when(count($this->selectedLanguageFilters) > 0, function($query) {
                $query->where(function($q) {
                    $q->whereIn('quell_sprache', $this->selectedLanguageFilters)
                      ->orWhereIn('ziel_sprache', $this->selectedLanguageFilters);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $auftraege = $query->paginate($this->perPage);

        return view('livewire.auftraege-table', [
            'auftraege' => $auftraege
        ]);
    }

    public function loadAuftragData()
    {
        $auftraege = Auftrag::all();

        foreach ($auftraege as $auftrag) {
            $this->anmerkungen[$auftrag->id] = $auftrag->anmerkungen ?? '';
            $this->angerufen[$auftrag->id]   = $auftrag->angerufen ? true : false;
        }
    }

    public function loadAllLanguages()
    {
        $this->allLanguages = Sprachen::orderBy('bez_lang')->get();
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function filterByPriority($priority)
    {
        $this->priorityFilter = $priority;
        $this->resetPage();
    }

    public function applyLanguageFilter()
    {
        $this->selectedLanguageFilters = $this->tempSelectedLanguages;
        $this->tempSelectedLanguages   = [];
        $this->languageSearchTerm      = '';
        $this->resetPage();
    }

    public function removeLanguageFilter($langId)
    {
        $this->selectedLanguageFilters = array_filter(
            $this->selectedLanguageFilters,
            fn($id) => $id != $langId
        );
        $this->resetPage();
    }

    public function clearLanguageFilters()
    {
        $this->selectedLanguageFilters = [];
        $this->tempSelectedLanguages   = [];
        $this->languageSearchTerm      = '';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getAuftraegeQuery()
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    protected function getAuftraegeQuery()
    {
        return Auftrag::query()
            ->when($this->search, function($query) {
                $searchTerm = $this->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('auftragsnummer', 'like', '%'.$searchTerm.'%')
                      ->orWhere('titel', 'like', '%'.$searchTerm.'%')
                      ->orWhereHas('kunde', function($subQ) use ($searchTerm) {
                          $subQ->where('firmenname', 'like', '%'.$searchTerm.'%');
                      });
                });
            })
            ->when($this->statusFilter, function($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function($query) {
                return $query->where('prioritaet', $this->priorityFilter);
            })
            ->when(count($this->selectedLanguageFilters) > 0, function($query) {
                $query->where(function($q) {
                    $q->whereIn('quell_sprache', $this->selectedLanguageFilters)
                      ->orWhereIn('ziel_sprache', $this->selectedLanguageFilters);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function bulkDelete()
    {
        if (count($this->selectedItems) > 0) {
            Auftrag::whereIn('id', $this->selectedItems)->delete();
            $this->reset('selectedItems', 'selectAll');
            session()->flash('message', 'Die ausgewählten Aufträge wurden gelöscht.');
            $this->emit('refreshTable');
        }
    }

    public function deleteSingle($id)
    {
        $auftrag = Auftrag::findOrFail($id);
        $auftrag->delete();
        session()->flash('message', "Auftrag mit der ID {$id} wurde gelöscht.");
        $this->emit('refreshTable');
    }

    public function updateStatus($auftragId, $newStatus)
    {
        $validStatuses = ['Neu', 'InBearbeitung', 'Abgeschlossen', 'Storniert'];

        if (!in_array($newStatus, $validStatuses)) {
            session()->flash('error', 'Ungültiger Status.');
            return;
        }

        $auftrag = Auftrag::find($auftragId);
        if ($auftrag) {
            $auftrag->update(['status' => $newStatus]);
            session()->flash('message', 'Status erfolgreich aktualisiert.');
            $this->emit('refreshTable');
        }
    }

    public function updatePriority($auftragId, $priority)
    {
        $validPriorities = ['niedrig', 'normal', 'hoch', 'dringend'];

        if (!in_array($priority, $validPriorities)) {
            return;
        }

        $auftrag = Auftrag::find($auftragId);
        if ($auftrag) {
            $auftrag->prioritaet = $priority;
            $auftrag->save();
            session()->flash('message', 'Priorität erfolgreich aktualisiert.');
            $this->emit('refreshTable');
        }
    }

    public function toggleAngerufen($auftragId)
    {
        $auftrag = Auftrag::find($auftragId);
        if ($auftrag) {
            $auftrag->update(['angerufen' => !$auftrag->angerufen]);
        }
    }

    public function updated($field)
    {
        if (strpos($field, 'angerufen.') === 0) {
            $auftragId = str_replace('angerufen.', '', $field);
            $this->saveAngerufen($auftragId);
        }
    }

    public function saveAngerufen($id)
    {
        $auftrag = Auftrag::find($id);
        if ($auftrag) {
            $auftrag->angerufen = $this->angerufen[$id] ?? false;
            $auftrag->save();
        }
    }

    public function saveNote($id)
    {
        $auftrag = Auftrag::find($id);
        if ($auftrag) {
            $auftrag->anmerkungen = $this->anmerkungen[$id] ?? '';
            $auftrag->save();
            session()->flash('message', 'Notiz gespeichert.');
        }
    }

    public function formatDate($date)
    {
        return optional(Carbon::parse($date))->format('d.m.Y');
    }

    public function printAuftrag($auftragId)
    {
        session()->flash('message', "Druckauftrag für ID {$auftragId} gestartet.");
    }

    public function quellSprache()
{
    return $this->belongsTo(Sprache::class, 'quell_sprache');
}

public function zielSprache()
{
    return $this->belongsTo(Sprache::class, 'ziel_sprache');
}

}
