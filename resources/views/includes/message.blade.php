@if(session('success') || isset($success))
<div class="alert alert-success"> {{ session('success') ?? $success }} </div>
@endif

@if(session('info') || isset($info))
<div class="alert alert-info"> {{ session('info') ?? $info }} </div>
@endif

@if(session('error') || isset($error))
<div class="alert alert-danger"> {{ session('error') ?? $error }} </div>
@endif
