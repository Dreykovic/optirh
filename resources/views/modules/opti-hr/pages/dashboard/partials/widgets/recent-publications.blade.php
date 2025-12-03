{{-- Widget: Publications recentes --}}
<div class="card dashboard-card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="icofont-newspaper text-primary"></i>
            Publications recentes
        </h5>
        <a href="{{ route('publications.config.index') }}" class="btn btn-sm btn-outline-primary">
            Voir tout
        </a>
    </div>
    <div class="card-body">
        @if(isset($recentPublications) && $recentPublications->count() > 0)
            @foreach($recentPublications as $publication)
                <div class="publication-item">
                    <div class="title">{{ Str::limit($publication->title, 60) }}</div>
                    <div class="meta">
                        <span>
                            <i class="icofont-user-alt-3"></i>
                            {{ $publication->author->first_name ?? 'Auteur' }} {{ $publication->author->last_name ?? '' }}
                        </span>
                        <span>
                            <i class="icofont-calendar"></i>
                            {{ $publication->published_at ? $publication->published_at->format('d M Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="icofont-newspaper d-block"></i>
                <div class="message">Aucune publication recente</div>
            </div>
        @endif
    </div>
</div>
