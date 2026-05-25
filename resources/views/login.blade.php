<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme Étudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3f51b5;
            --secondary-color: #ff4081;
            --accent-color: #4caf50;
            --dark-color: #333333;
            --gradient: linear-gradient(135deg, #3f51b5, #2196f3);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Centrage du contenu principal */
        .main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }

        .form-title { color: var(--primary-color); font-weight: 600; text-align: center; margin-bottom: 1.5rem; }
        .form-subtitle { color: #666; text-align: center; margin-bottom: 1.8rem; }
        .form-control { border-radius: 10px; padding: 1rem 0.75rem; border: 1.5px solid #e0e0e0; }

        .btn-primary { background: var(--gradient); border: none; border-radius: 10px; padding: 0.8rem; width: 100%; font-weight: 600; }

        .btn-google {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            background-color: #4285F4; color: #fff; font-weight: 600; padding: 0.8rem;
            border-radius: 8px; text-decoration: none; width: 100%;
        }

        .separator { display: flex; align-items: center; margin: 1.5rem 0; color: #999; font-size: 0.9rem; }
        .separator::before, .separator::after { content: ''; flex: 1; height: 1px; background-color: #e0e0e0; }
        .separator span { padding: 0 1rem; }

        .fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <div class="form-card fade-in">
            <h2 class="form-title">Inscription</h2>
            <p class="form-subtitle">Rejoignez notre communauté étudiante internationale</p>

            <a href="https://guidemebackend-1.onrender.com/api/auth/google" class="btn-google">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" style="width:20px;">
                S'inscrire avec Google
            </a>

            <div class="separator"><span>ou</span></div>

            <form id="registerForm">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="lastName" placeholder="Nom" required>
                    <label for="lastName"><i class="fas fa-user me-2"></i>Nom</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="registerEmail" placeholder="nom@exemple.com" required>
                    <label for="registerEmail"><i class="fas fa-envelope me-2"></i>Adresse email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="registerPassword" placeholder="Mot de passe" required>
                    <label for="registerPassword"><i class="fas fa-lock me-2"></i>Mot de passe</label>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>

            <div class="text-center mt-3">
                <p>Vous avez déjà un compte ? <a href="#login">Connectez-vous ici</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
