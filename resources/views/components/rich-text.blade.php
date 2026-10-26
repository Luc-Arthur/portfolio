{{-- Texte saisi dans l'admin, mis en forme (voir App\Support\RichText). lead : premier paragraphe en avant. --}}
@props(['text' => null, 'lead' => false])

@php($blocks = \App\Support\RichText::parse($text))

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @foreach ($blocks as $block)
        @if ($block['type'] === 'heading')
            <h2 class="!mt-14 flex items-center gap-4 font-serif text-3xl text-paper first:!mt-0">
                <span class="h-px w-8 shrink-0 bg-brass"></span>{{ $block['text'] }}
            </h2>
        @elseif ($block['type'] === 'item')
            <p class="flex items-start gap-3 text-paper">
                <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rotate-45 bg-brass"></span>{{ $block['text'] }}
            </p>
        @elseif ($block['type'] === 'term')
            <p class="border-l border-white/10 pl-5 leading-relaxed text-paper-dim">
                <span class="font-semibold text-paper">{{ $block['term'] }}</span> — {{ $block['text'] }}
            </p>
        @else
            <p class="text-lg leading-relaxed {{ $lead && $loop->first ? 'text-xl text-paper' : 'text-paper-dim' }}">
                {{ $block['text'] }}
            </p>
        @endif
    @endforeach

    @if (empty($blocks))
        {{ $slot }}
    @endif
</div>
