@if ($paginator->hasPages())
<nav aria-label="Pagination" class="d-flex justify-content-center mt-4">
    <ul class="pagination pagination-sm mb-0" style="gap:3px;">

        {{-- Première page --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link" style="border-radius:8px;border:1px solid #e0d8f5;color:#aaa;padding:6px 12px;">
                <i class="bi bi-chevron-double-left"></i>
            </span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url(1) }}" title="Première page"
               style="border-radius:8px;border:1px solid #e0d8f5;color:#4C3B7F;padding:6px 12px;">
                <i class="bi bi-chevron-double-left"></i>
            </a>
        </li>
        @endif

        {{-- Page précédente --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link" style="border-radius:8px;border:1px solid #e0d8f5;color:#aaa;padding:6px 12px;">
                <i class="bi bi-chevron-left"></i> Précédent
            </span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}"
               style="border-radius:8px;border:1px solid #e0d8f5;color:#4C3B7F;padding:6px 12px;">
                <i class="bi bi-chevron-left"></i> Précédent
            </a>
        </li>
        @endif

        {{-- Numéros de pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
            <li class="page-item disabled">
                <span class="page-link" style="border-radius:8px;border:none;color:#aaa;">…</span>
            </li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item active">
                    <span class="page-link" style="border-radius:8px;background:linear-gradient(135deg,#4C3B7F,#6B5BA8);border:none;color:white;padding:6px 12px;font-weight:600;">
                        {{ $page }}
                    </span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}"
                       style="border-radius:8px;border:1px solid #e0d8f5;color:#4C3B7F;padding:6px 12px;transition:all .2s;"
                       onmouseover="this.style.background='#f0ecfb'" onmouseout="this.style.background=''">
                        {{ $page }}
                    </a>
                </li>
                @endif
                @endforeach
            @endif
        @endforeach

        {{-- Page suivante --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}"
               style="border-radius:8px;border:1px solid #e0d8f5;color:#4C3B7F;padding:6px 12px;">
                Suivant <i class="bi bi-chevron-right"></i>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link" style="border-radius:8px;border:1px solid #e0d8f5;color:#aaa;padding:6px 12px;">
                Suivant <i class="bi bi-chevron-right"></i>
            </span>
        </li>
        @endif

        {{-- Dernière page --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="Dernière page"
               style="border-radius:8px;border:1px solid #e0d8f5;color:#4C3B7F;padding:6px 12px;">
                <i class="bi bi-chevron-double-right"></i>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link" style="border-radius:8px;border:1px solid #e0d8f5;color:#aaa;padding:6px 12px;">
                <i class="bi bi-chevron-double-right"></i>
            </span>
        </li>
        @endif

    </ul>
</nav>
<p class="text-center text-muted small mt-2">
    Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
    — {{ $paginator->total() }} résultat(s) au total
</p>
@endif
