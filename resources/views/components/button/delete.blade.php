<form style="display:inline; box-shadow: none; " action="{{ $href }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');" method="POST">
    @method('DELETE')
    @csrf
    <button {{ $tooltip ? "data-mdb-tooltip-init title=$tooltip": '' }} class="btn btn-secondary text-danger @isset($class) {{ $class }} @endisset"> <i class="fa fa-trash"></i> {{ $slot ?? '' }} </button>
</form>
