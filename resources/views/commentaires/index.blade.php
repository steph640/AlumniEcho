@extends('template')

@section('main')
<main class="container my-5">
    <h2 class="mb-4 text-center">Gestion des Commentaires</h2>

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
        <a href="{{ url('/web/commentaires/create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Ajouter un commentaire</a>
    </div>

    @if($commentaires->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucun commentaire trouvé.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Code</th>
                        <th>Contenu</th>
                        <th>Souvenir</th>
                        <th>Auteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($commentaires as $c)
                        <tr>
                            <td class="fw-bold">{{ $c->code_com }}</td>
                            <td>{{ Str::limit($c->contenu_com, 60) }}</td>
                            <td><span class="badge bg-secondary">{{ $c->code_souv }}</span></td>
                            <td>{{ $c->code_user }}</td>
                            <td>
                                <a href="{{ url('/web/commentaires/'.$c->code_com.'/edit') }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <form action="{{ url('/web/commentaires/'.$c->code_com) }}" method="POST" style="display:inline" class="delete-form">
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
                    {{ $commentaires->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif
</main>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
