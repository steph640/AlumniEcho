@extends('template')

@section('main')

    <h2 class="mb-4 text-center">Gestion des Filières</h2>

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
        <a href="{{ url('/web/filieres/create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Ajouter une filière</a>
    </div>

    @if($filieres->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucune filière trouvée.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="" text-center">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($filieres as $f)
                        <tr>
                            <td class="fw-bold">{{ $f->code_fil }}</td>
                            <td>{{ $f->nom_fil }}</td>
                            <td>{{ Str::limit($f->description_fil, 60) }}</td>
                            <td>
                                <a href="{{ url('/web/filieres/'.$f->code_fil.'/edit') }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <form action="{{ url('/web/filieres/'.$f->code_fil) }}" method="POST" style="display:inline" class="delete-form">
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
                    {{ $filieres->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif


<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette filière ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
