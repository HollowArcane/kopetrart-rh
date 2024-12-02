<button {{ isset($id) ? 'id='.$id: '' }} type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#{{ $target }}">
    {{ $slot }}
</button>
