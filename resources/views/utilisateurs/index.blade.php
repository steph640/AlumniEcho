@extends('template')

@section('main')

    <h2 class="mb-4 text-center">Gestion des Utilisateurs</h2>

    @if(session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show shadow position-fixed top-0 end-0 m-3" style="z-index: 2000; min-width: 300px;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                if (modal) modal.hide();
            }, 5000);
        </script>
    @endif

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-plus-circle me-2"></i> Ajouter un utilisateur
        </button>
    </div>

    @if($utilisateurs_list->isEmpty())
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Aucun utilisateur trouvé.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white shadow-sm">
                <thead class="" text-center">
                    <tr>
                        <th>Code</th>
                        <th>Nom & Prénom</th>
                        <th>Login</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($utilisateurs_list as $utilisateur)
                        <tr>
                            <td class="fw-bold">{{ $utilisateur->code_user }}</td>
                            <td>{{ $utilisateur->nom_user }} {{ $utilisateur->prenom_user }}</td>
                            <td>{{ $utilisateur->login_user }}</td>
                            <td>{{ $utilisateur->tel_user ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($utilisateur->role_user) }}</span></td>
                            <td>
                                <span class="badge {{ $utilisateur->etat_user == 'actif' ? 'bg-success' : ($utilisateur->etat_user == 'bloquer' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($utilisateur->etat_user) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                        data-code="{{ $utilisateur->code_user }}"
                                        data-nom="{{ $utilisateur->nom_user }}"
                                        data-prenom="{{ $utilisateur->prenom_user }}"
                                        data-login="{{ $utilisateur->login_user }}"
                                        data-tel="{{ $utilisateur->tel_user }}"
                                        data-sexe="{{ $utilisateur->sexe_user }}"
                                        data-role="{{ $utilisateur->role_user }}"
                                        data-etat="{{ $utilisateur->etat_user }}">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </button>
                                <form action="/web/utilisateurs/{{ $utilisateur->code_user }}" method="POST" style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
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
                    {{ $utilisateurs_list->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    @endif


<!-- Modale d'ajout -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/web/utilisateurs" method="POST">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Nouvel Utilisateur</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Code</label>
                            <input type="text" name="code_user" class="form-control" placeholder="Ex: U001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom_user" class="form-control" placeholder="Nom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="prenom_user" class="form-control" placeholder="Prénom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Login</label>
                            <input type="text" name="login_user" class="form-control" placeholder="Identifiant" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mot de passe</label>
                            <input type="password" name="password_user" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="text" name="tel_user" class="form-control" placeholder="Ex: 699000000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sexe</label>
                            <select name="sexe_user" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rôle</label>
                            <select name="role_user" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="utilisateur">Utilisateur</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">État</label>
                            <select name="etat_user" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                                <option value="bloquer">Bloqué</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modale d'édition -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Modifier l'Utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom_user" id="editNom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="prenom_user" id="editPrenom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Login</label>
                            <input type="text" name="login_user" id="editLogin" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="text" name="tel_user" id="editTel" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sexe</label>
                            <select name="sexe_user" id="editSexe" class="form-select" required>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rôle</label>
                            <select name="role_user" id="editRole" class="form-select" required>
                                <option value="utilisateur">Utilisateur</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">État</label>
                            <select name="etat_user" id="editEtat" class="form-select" required>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                                <option value="bloquer">Bloqué</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Modale d'édition
    const editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const code = button.getAttribute('data-code');
        document.getElementById('editUserForm').action = `/web/utilisateurs/${code}`;
        document.getElementById('editNom').value = button.getAttribute('data-nom');
        document.getElementById('editPrenom').value = button.getAttribute('data-prenom');
        document.getElementById('editLogin').value = button.getAttribute('data-login');
        document.getElementById('editTel').value = button.getAttribute('data-tel');
        document.getElementById('editSexe').value = button.getAttribute('data-sexe');
        document.getElementById('editRole').value = button.getAttribute('data-role');
        document.getElementById('editEtat').value = button.getAttribute('data-etat');
    });

    // Fermeture modale après suppression
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
