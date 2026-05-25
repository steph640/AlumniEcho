@extends('template')

@section('main')

    <h2 class="mb-4 text-center">Gestion des Promotions</h2>

    @if(session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show shadow position-fixed top-0 end-0 m-3" style="z-index: 2000; min-width: 300px;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(function() {
                location.reload();
            }, 5000);
        </script>
    @endif

    <div class="mb-3">
        <a href="{{ url('/web/promotions/create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Ajouter une promotion</a>
    </div>

    @if($promotions->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucune promotion trouvée.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="" text-center">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Année</th>
                        <th>Filière</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($promotions as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->code_promo }}</td>
                            <td>{{ $p->nom_promo }}</td>
                            <td><span class="badge bg-secondary">{{ $p->annee_promo }}</span></td>
                            <td>{{ $p->code_fil }}</td>
                            <td>
                                <a href="{{ url('/web/promotions/' . $p->code_promo . '/edit') }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <form action="{{ url('/web/promotions/' . $p->code_promo) }}" method="POST" style="display:inline" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash me-1"></i>Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="pagination">
                <ul class="pagination">
                    {{ $promotions->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif


<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
