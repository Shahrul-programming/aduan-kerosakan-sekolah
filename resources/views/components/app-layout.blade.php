@props(['header' => null])

@if($header)
    <x-layouts.app>
        @slot('header')
            {{ $header }}
        @endslot
        {{ $slot }}
    </x-layouts.app>
@else
    <x-layouts.app>
        {{ $slot }}
    </x-layouts.app>
@endif
