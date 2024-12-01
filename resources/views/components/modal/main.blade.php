<div class="modal fade" id="{{ $name }}" name="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-{{ $type ?? 'white' }}">
                <h5 class="modal-title {{ isset($type) ? 'text-white': 'z<' }}" id="{{ $name }}-title"> {{ $title ?? '' }} </h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <div class="w-100 d-flex justify-content-center">
                    <button id="{{ $name }}-btn-cancel" type="button" class="m-2 btn btn-outline-{{ $type ?? 'primary' }}" data-mdb-ripple-init data-mdb-dismiss="modal"> Annuler </button>
                    <button id="{{ $name }}-btn-submit" type="button" class="m-2 btn btn-{{ $type ?? 'primary' }}" data-mdb-ripple-init> Valider </button>
                </div>
            </div>
        </div>
    </div>
</div>
