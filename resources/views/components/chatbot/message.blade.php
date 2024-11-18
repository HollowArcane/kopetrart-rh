<div class="row {{ $message->user_sender == 'AI' ? 'pr-5' : 'pl-5' }} mb-3">
    <div class="message-item rounded {{ $message->user_sender == 'AI' ? 'bg-lightgrey' : 'bg-primary text-white' }}">
        <div class="message-item-head">
            {{-- <span class="message-item-user">
                {{ $message->user }}
            </span> --}}
            <small class="message-item-time">
                {{ (new DateTime($message->created_at))->format('H:i') }}
            </small>
        </div>
        <div class="message-item-body p-3">
            {{ $message->content }}
        </div>
    </div>
</div>
