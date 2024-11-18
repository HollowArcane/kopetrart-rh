<div class="progress" style="height: 20px">
    <div
      class="progress-bar progress-bar-striped progress-bar-animated {{ ($failed ?? false) ? 'bg-secondary': '' }}"
      role="progressbar"
      aria-valuenow="{{ $progress }}"
      aria-valuemin="0"
      aria-valuemax="100"
      style="width: {{ $progress }}%;"
    > {{ $progress }}% </div>
</div>
