{{-- Carte projet : $project, $index (facultatif), $wide (facultatif : format panoramique) --}}
<article class="group relative">
    <a href="{{ route('projects.show', $project) }}" class="block">
        <div class="relative {{ !empty($wide) ? 'aspect-[16/10] md:aspect-[21/9]' : 'aspect-[16/10]' }} overflow-hidden rounded-2xl border border-white/5 bg-ink-800">
            @if ($project->image)
                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy"
                    class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-ink-700 to-ink-900">
                    <span class="font-serif text-6xl text-white/10">{{ mb_substr($project->title, 0, 1) }}</span>
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-transparent opacity-0 transition duration-500 group-hover:opacity-100"></div>

            @if ($project->video_url)
                <span class="chip absolute left-4 top-4 !bg-ink-950/70 backdrop-blur">
                    <x-icon name="play" class="mr-1.5 h-3 w-3 text-brass" /> Vidéo
                </span>
            @endif

            <span
                class="absolute bottom-4 right-4 flex h-11 w-11 translate-y-3 items-center justify-center rounded-full bg-brass text-ink-950 opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                <x-icon name="arrow-up-right" class="h-5 w-5" />
            </span>
        </div>

        <div class="mt-5 flex items-start justify-between gap-4">
            <div>
                <p class="label-mono">
                    @isset($index)
                        <span class="text-brass">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span> ·
                    @endisset
                    {{ $project->category?->name ?? 'Projet' }}
                    @if ($project->date)
                        · {{ $project->date->format('Y') }}
                    @endif
                </p>
                <h3 class="mt-2 font-serif text-3xl leading-tight text-paper transition group-hover:text-brass-light">
                    {{ $project->title }}
                </h3>
            </div>
        </div>

        @if ($project->techList())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (array_slice($project->techList(), 0, 4) as $tech)
                    <span class="chip">{{ $tech }}</span>
                @endforeach
            </div>
        @endif
    </a>
</article>
