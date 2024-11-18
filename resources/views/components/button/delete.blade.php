<form style="display:inline" action="{{ $href }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');" method="POST">
    @method('DELETE')
    @csrf
    <button class="btn btn-light text-danger @isset($class) {{ $class }} @endisset"> <i class="fa fa-trash"></i> {{ $slot ?? '' }} </button>
</form>