@extends('template')

@section('main')
<main class="container my-5">
    <h2 class="mb-4 text-center">Gestion des FAQ Chatbot</h2>

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
        <a href="{{ url('/web/chatbot_faqs/create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Ajouter une FAQ</a>
    </div>

    @if($faqs->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucune FAQ trouvée.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Code</th>
                        <th>Question</th>
                        <th>Réponse</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($faqs as $f)
                        <tr>
                            <td class="fw-bold">{{ $f->code_faq }}</td>
                            <td>{{ Str::limit($f->question_faq, 60) }}</td>
                            <td>{{ Str::limit($f->reponse_faq, 60) }}</td>
                            <td>
                                <a href="{{ url('/web/chatbot_faqs/' . $f->code_faq . '/edit') }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <form action="{{ url('/web/chatbot_faqs/' . $f->code_faq) }}" method="POST" style="display:inline" class="delete-form">
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
                    {{ $faqs->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif
</main>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette FAQ ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
