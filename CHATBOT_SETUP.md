# 🤖 AlumniEcho - Chatbot Intelligent avec Google Gemini

## Vue d'ensemble

Le nouveau système de chatbot pour AlumniEcho utilise **Google Gemini AI** pour générer des réponses intelligentes et contextuelles. Le chatbot peut maintenant :

✅ Répondre à des questions variées sans liste prédéfinie  
✅ Accéder aux données de la base de données (promotions, filières, utilisateurs)  
✅ Comprendre des questions complexes comme "Quels sont les élèves de la promotion 2023?"  
✅ Apprendre avec le feedback des utilisateurs  
✅ Basculer automatiquement sur FAQ si l'API Gemini est indisponible  

---

## Configuration

### 1. Clé API Google Gemini

Obtenez votre clé API gratuitement :
1. Allez sur [Google AI Studio](https://aistudio.google.com)
2. Créez une clé API gratuite
3. Ajoutez-la à votre fichier `.env` :

```env
GEMINI_API_KEY=votre_clé_api_ici
```

### 2. Installation des dépendances

Les dépendances requises sont déjà incluses dans Laravel :
- `guzzlehttp/guzzle` (pour les appels HTTP)

Pas besoin d'installer de packages supplémentaires !

### 3. Exécution des migrations

```bash
php artisan migrate
```

Cela va ajouter les colonnes `response_type`, `is_helpful`, et `user_feedback` à la table `message_chatbots`.

### 4. Test de connexion

Testez la connexion à Gemini :

```bash
php artisan gemini:test
```

Vous devriez voir :
```
✅ Connexion réussie à Google Gemini API!
```

---

## Architecture

### Services

#### 1. **GeminiChatbotService**
- Gère la communication avec l'API Google Gemini
- Construit les prompts système optimisés
- Gère les timeouts et erreurs

```php
$geminiService = new GeminiChatbotService();
$response = $geminiService->generateResponse($question, $context);
```

#### 2. **DatabaseQueryService**
- Récupère les données de la BD (promotions, filières, utilisateurs)
- Exécute les requêtes sécurisées
- Fournit les statistiques du site

```php
$dbService = new DatabaseQueryService();
$promotions = $dbService->getPromotions();
$usersByPromo = $dbService->getUsersByPromotion($promotionCode);
```

#### 3. **QuestionAnalysisService**
- Analyse les questions pour extraire les intentions
- Détecte les types de requêtes (promotion, filière, statistiques)
- Enrichit le contexte avec les données pertinentes

```php
$analysisService = new QuestionAnalysisService();
$analysis = $analysisService->analyzeQuestion("Quels sont les élèves de 2023?");
```

---

## Endpoints API

### 1. Poser une question au chatbot

**POST** `/api/chatbot/ask`

```bash
curl -X POST http://localhost:8003/api/chatbot/ask \
  -H "Content-Type: application/json" \
  -d '{
    "question": "Quels sont les élèves appartenant à la promotion 2023?",
    "use_ai": true
  }'
```

**Réponse :**
```json
{
  "reponse": "Based on the available data, here are the students from promotion 2023...",
  "code_message": "MSG123456789abc",
  "faq_found": false,
  "ai_generated": true
}
```

**Paramètres :**
- `question` (string, requis) : La question de l'utilisateur
- `use_ai` (boolean, optionnel) : Utiliser Gemini (true) ou FAQ (false). Par défaut : true

### 2. Récupérer les FAQs

**GET** `/api/chatbot/faqs`

```json
[
  {
    "code_faq": "FAQ001",
    "question_faq": "Comment créer un compte?",
    "reponse_faq": "Pour créer un compte..."
  }
]
```

### 3. Historique du chatbot (authentifié)

**GET** `/api/chatbot/history`

Nécessite une authentification avec token Sanctum.

```json
[
  {
    "code_message": "MSG123456789abc",
    "question_chatbot": "Qui est l'administrateur?",
    "reponse_chatbot": "L'administrateur est...",
    "response_type": "ai",
    "is_helpful": true,
    "created_at": "2026-06-02T10:30:00Z"
  }
]
```

---

## Types de questions supportées

### Questions sur les promotions
```
"Quels sont les élèves de la promo 2023?"
"Combien d'élèves dans la promotion 2024?"
"Donne-moi la liste des étudiants de 2022"
```

### Questions sur les filières
```
"Quels sont les étudiants de la filière informatique?"
"Combien de filières sont disponibles?"
"Liste-moi les filières"
```

### Questions statistiques
```
"Combien d'utilisateurs sur le site?"
"Nombre total de souvenirs?"
"Quelles sont les statistiques globales?"
```

### Questions générales
```
"Qu'est-ce qu'AlumniEcho?"
"Comment fonctionne le site?"
"Aide-moi avec le chatbot"
```

---

## Contrôle de la qualité

### Feedback utilisateur

Les utilisateurs peuvent laisser du feedback :

```bash
curl -X PATCH http://localhost:8003/api/message-chatbots/MSG123456789abc \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "is_helpful": true,
    "user_feedback": "Réponse très utile!"
  }'
```

### Logs

Les logs détaillés sont disponibles dans `storage/logs/laravel.log` :

```
[2026-06-02 10:30:00] local.INFO: Question Analysis: {...}
[2026-06-02 10:30:00] local.INFO: Enriched Context Keys: [...]
```

---

## Limitation et considérations

### Limite gratuite
- **60 requêtes par minute** (Google Gemini free tier)
- **100 requêtes par jour** recommandées pour le développement

### Fallback automatique
Si l'API Gemini ne répond pas :
1. Le système essaye de chercher dans les FAQs
2. Si aucune FAQ ne correspond, une réponse par défaut est envoyée

### Sécurité
- Les données envoyées à Gemini sont publiques (pas d'informations sensibles)
- Les requêtes à la BD sont filtrées et limitées
- Authentification Sanctum requise pour l'historique

---

## Améliorations futures

🔮 **À venir :**
- [ ] Fine-tuning du modèle avec vos FAQs
- [ ] Support du multi-langage (FR, EN, AR)
- [ ] Mémorisation du contexte utilisateur (multi-turn)
- [ ] Intégration avec les événements du site
- [ ] Analytics et métriques de satisfaction
- [ ] Support des images et documents
- [ ] Cache des réponses pour optimiser les coûts

---

## Dépannage

### ❌ "API key not configured"
→ Vérifiez que `GEMINI_API_KEY` est définie dans `.env`

### ❌ "Failed to get response from Gemini API"
→ Vérifiez votre connexion internet et le quota de l'API

### ❌ "Unexpected response format"
→ Vérifiez les logs : `tail -f storage/logs/laravel.log`

### ❌ Le chatbot répond lentement
→ C'est normal (Gemini peut prendre 2-3 secondes). Consultez les logs pour voir les timings.

---

## Support

Pour des questions ou des bugs, consultez les logs Laravel :

```bash
tail -f storage/logs/laravel.log
```

Ou lancez le test de connexion :

```bash
php artisan gemini:test
```
