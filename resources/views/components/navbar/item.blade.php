<li class="@if($href == $active) active @endif">
    <a
        class="pf__navbar-item"
        href="{{ $href }}"
    >
        {{ $slot }}
    </a>
</li>
