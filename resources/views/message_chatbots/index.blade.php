@extends('template')

@section('main')
<main class="container my-5">
    <h2 class="mb-4 text-center">Gestion des Messages Chatbot</h2>

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
        <a href="{{ url('/web/message_chatbots/create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Ajouter un message</a>
    </div>

    @if($messages->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucun message trouvé.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Code</th>
                        <th>Contenu</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($messages as $m)
                        <tr>
                            <td class="fw-bold">{{ $m->code_message }}</td>
                            <td>{{ Str::limit($m->contenu_msg, 60) }}</td>
                            <td>{{ $m->code_user }}</td>
                            <td>{{ $m->created_at ? $m->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ url('/web/message_chatbots/' . $m->code_message . '/edit') }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <form action="{{ url('/web/message_chatbots/' . $m->code_message) }}" method="POST" style="display:inline" class="delete-form">
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
                    {{ $messages->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif
</main>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
