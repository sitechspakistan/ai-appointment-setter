{{--
    Font Awesome icon picker (dropdown grid).
    Params:
      name            hidden input name (default 'icon')
      value           currently selected icon name (e.g. 'snowflake')
      submitOnSelect  true = submit the enclosing <form> as soon as an icon is picked
--}}
@php($name = $name ?? 'icon')
@php($current = ($value ?? null) && array_key_exists($value, \App\Models\Service::ICONS) ? $value : null)

<div class="wf-iconpicker dropdown" data-wf-iconpicker @if($submitOnSelect ?? false) data-icon-submit @endif>
    <input type="hidden" name="{{ $name }}" value="{{ $current }}" data-icon-value>

    <button type="button" class="btn wf-iconpicker__toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Choose an icon">
        <i class="{{ $current ? 'fa-solid fa-'.$current : 'fa-solid fa-icons' }}" data-icon-preview></i>
    </button>

    <div class="dropdown-menu p-2 wf-iconpicker__menu">
        <div class="wf-iconpicker__grid">
            @foreach (\App\Models\Service::ICONS as $ic => $label)
                <button type="button" class="wf-iconpicker__item {{ $current === $ic ? 'is-active' : '' }}" data-icon="{{ $ic }}" title="{{ $label }}">
                    <i class="fa-solid fa-{{ $ic }}"></i>
                </button>
            @endforeach
        </div>
    </div>
</div>
