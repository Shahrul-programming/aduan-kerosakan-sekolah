@props(['messages'])

@if ($messages)
    @props(['messages'])

@if ($messages)
    <div class="mt-2 text-sm text-red-600 animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside space-y-1">
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@endif
