{{-- resources/views/partials/sort-icon.blade.php --}}
@if ($sortField === $field)
    @if ($sortDirection === 'asc')
        <i class="bi bi-caret-up-fill"></i>
    @else
        <i class="bi bi-caret-down-fill"></i>
    @endif
@endif
