<form {{ isset($id) ? 'id='.$id: '' }} action="{{ $action ?? '' }}" method="POST" enctype="multipart/form-data">
    @method($method ?? 'POST')
    @csrf

    {{ $slot }}
</form>
