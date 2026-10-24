<x-mail::message>
# Nouveau message depuis le portfolio

**De :** {{ $name }} ({{ $email }})

@if ($subject)
**Sujet :** {{ $subject }}
@endif

{{ $body }}

<x-mail::button :url="'mailto:' . $email">
Répondre
</x-mail::button>
</x-mail::message>
