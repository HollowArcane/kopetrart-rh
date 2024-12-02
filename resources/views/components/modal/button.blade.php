<button <?= $tooltip ? "data-mdb-tooltip-init title=\"$tooltip\"": '' ?> {{ isset($id) ? 'id='.$id: '' }} type="button" class="btn btn-{{ $type ?? 'primary' }}" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#{{ $target }}">
    {{ $slot }}
</button>
