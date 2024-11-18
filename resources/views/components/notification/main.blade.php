<div class="rh__notification">
    <button id="notification-button" class="btn shadow-0">
        <span class="text fs-4">
            {{ $slot }}
            @if ($notifications->some(fn($row) => $row->date_seen == null))
                <span class="badge bg-danger badge-dot"></span>
            @endif
        </span>

    </button>
    <div id="notification-wrap" class="rh__notification-wrap shadow-4 bg-white rounded">
    @if ($notifications->count() > 0)
        @foreach ($notifications as $notification)
        <a href="/notification/{{ $notification->id }}">
            <div class="rh__notification-item p-3 border-bottom">
                <p class="mb-2 text-muted"> {{ $notification->datetime }} </p>
                    <h5>
                        @if ($notification->date_seen == null)
                        <span class="badge badge-danger">New</span>
                        @endif
                        {{ $notification->title }}
                    </h5>
                <p class="text-muted"> {{ $notification->message }} </p>
            </div>
        </a>
        @endforeach
    @else
        <p class="text-center text-muted mt-5">Aucune notification.</p>
    @endif
    </div>
</div>

<script>
    const notificationButton = document.getElementById('notification-button');
    const notificationWrap = document.getElementById('notification-wrap');

    notificationButton.addEventListener('click', () => {
        notificationWrap.classList.toggle('show');
        notificationWrap.classList.toggle('slide-down');
    });
</script>
