# Laravel Response Handler

🚀 **Package Laravel professionnel pour la gestion standardisée des réponses JSON et exceptions dans vos APIs.**

## 📋 Table des matières

- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Exceptions disponibles](#exceptions-disponibles)
- [Réponses disponibles](#réponses-disponibles)
- [Formats de réponse](#formats-de-réponse)
- [Exemples avancés](#exemples-avancés)
- [Fonctionnalités](#fonctionnalités)
- [Contribuer](#contribuer)

## 🔧 Installation

```bash
composer require babaissandiaye/laravel-response-handler
```

Le service provider sera automatiquement enregistré grâce à la découverte automatique de Laravel.

## ⚙️ Configuration

### 1. Enregistrement du middleware (obligatoire)

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->append(\BabaissaNdiaye\LaravelResponseHandler\Http\Middleware\ResponseInterceptor::class);
})
```

### 2. Configuration de l'Exception Handler (optionnel)

```php
// app/Exceptions/Handler.php
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\ExceptionInterface;

public function render($request, Throwable $e)
{
    if ($e instanceof ExceptionInterface) {
        return response()->json([
            'success' => false,
            'error' => [
                'message' => $e->getErrorMessage(),
                'status_code' => $e->getStatusCode()
            ]
        ], $e->getStatusCode());
    }

    return parent::render($request, $e);
}
```

## 🎯 Utilisation

### ResponseFactory - Gestion des réponses de succès

```php
<?php

namespace App\Http\Controllers;

use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private ResponseFactory $responseFactory
    ) {}

    public function index()
    {
        $users = User::paginate(15);
        return $this->responseFactory->retrieved($users, 'Liste des utilisateurs récupérée');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create($validated);
        return $this->responseFactory->created($user, 'Utilisateur créé avec succès');
    }

    public function show(User $user)
    {
        return $this->responseFactory->success($user, 'Détails de l\'utilisateur');
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->validated());
        return $this->responseFactory->success($user, 'Utilisateur mis à jour');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->responseFactory->success(null, 'Utilisateur supprimé');
    }
}
```

### ExceptionFactory - Gestion des erreurs

```php
<?php

namespace App\Http\Controllers;

use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private ExceptionFactory $exceptionFactory
    ) {}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!Auth::attempt($credentials)) {
            throw $this->exceptionFactory->unauthorized('Identifiants invalides');
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            throw $this->exceptionFactory->alreadyExists('Un utilisateur avec cet email existe déjà');
        }

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function profile()
    {
        $user = Auth::user();
        
        if (!$user) {
            throw $this->exceptionFactory->unauthorized('Token invalide');
        }

        return response()->json($user);
    }
}
```

### Service Layer avec gestion d'erreurs

```php
<?php

namespace App\Services;

use App\Models\User;
use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;

class UserService
{
    public function __construct(
        private ExceptionFactory $exceptionFactory,
        private ResponseFactory $responseFactory
    ) {}

    public function findById(int $id): User
    {
        $user = User::find($id);
        
        if (!$user) {
            throw $this->exceptionFactory->notFound("Utilisateur avec l'ID {$id} introuvable");
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        if (User::where('email', $data['email'])->exists()) {
            throw $this->exceptionFactory->alreadyExists('Un utilisateur avec cet email existe déjà');
        }

        try {
            return User::create($data);
        } catch (\Exception $e) {
            throw $this->exceptionFactory->serverError('Erreur lors de la création de l\'utilisateur');
        }
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->findById($id);

        if (isset($data['email']) && $data['email'] !== $user->email) {
            if (User::where('email', $data['email'])->exists()) {
                throw $this->exceptionFactory->alreadyExists('Cet email est déjà utilisé');
            }
        }

        $user->update($data);
        return $user->fresh();
    }
}
```

## 🚨 Exceptions disponibles

| Exception | Code HTTP | Description |
|-----------|-----------|-------------|
| `BadRequestException` | 400 | Requête mal formée |
| `UnauthorizedException` | 401 | Authentification requise |
| `NotFoundException` | 404 | Ressource introuvable |
| `AlreadyExistsException` | 409 | Conflit - ressource existe |
| `UnprocessableEntityException` | 422 | Données invalides |
| `ServerErrorException` | 500 | Erreur serveur |

## ✅ Réponses disponibles

| Méthode | Code HTTP | Usage |
|---------|-----------|-------|
| `success()` | 200 | Opération réussie |
| `created()` | 201 | Ressource créée |
| `retrieved()` | 200 | Données récupérées |

## 📄 Formats de réponse

### Réponse de succès
```json
{
    "success": true,
    "status_code": 200,
    "message": "Utilisateurs récupérés avec succès",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "per_page": 15,
        "total": 1
    }
}
```

### Réponse d'erreur
```json
{
    "success": false,
    "error": {
        "message": "Utilisateur avec l'ID 999 introuvable",
        "status_code": 404
    }
}
```

### Erreur de validation
```json
{
    "success": false,
    "error": {
        "message": "Les données fournies sont invalides",
        "errors": {
            "email": ["Le champ email est requis"],
            "password": ["Le mot de passe doit contenir au moins 8 caractères"]
        },
        "status_code": 422
    }
}
```

## 🔥 Exemples avancés

### API Resource avec ResponseFactory

```php
<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;

class UserController extends Controller
{
    public function __construct(
        private ResponseFactory $responseFactory
    ) {}

    public function index()
    {
        $users = User::paginate(15);
        return $this->responseFactory->retrieved(
            new UserCollection($users),
            'Liste des utilisateurs'
        );
    }

    public function show(User $user)
    {
        return $this->responseFactory->success(
            new UserResource($user),
            'Détails de l\'utilisateur'
        );
    }
}
```

### Middleware personnalisé avec ExceptionFactory

```php
<?php

namespace App\Http\Middleware;

use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function __construct(
        private ExceptionFactory $exceptionFactory
    ) {}

    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()) {
            throw $this->exceptionFactory->unauthorized('Authentification requise');
        }

        if (!$request->user()->hasRole($role)) {
            throw $this->exceptionFactory->unauthorized("Accès refusé - rôle '{$role}' requis");
        }

        return $next($request);
    }
}
```

## 🎉 Fonctionnalités

- ✅ **Formatage automatique** des réponses JSON
- ✅ **Gestion centralisée** des exceptions
- ✅ **Masquage automatique** des mots de passe
- ✅ **Injection de dépendances** Laravel native
- ✅ **Compatible** Laravel 10, 11, 12
- ✅ **Types stricts** PHP 8.1+
- ✅ **Middleware intercepteur** automatique
- ✅ **Codes de statut HTTP** standards
- ✅ **Messages personnalisables**
- ✅ **Support de pagination** Laravel
- ✅ **Gestion des erreurs de validation**

## 🤝 Contribuer

Les contributions sont les bienvenues ! Voici comment procéder :

1. Fork le projet
2. Créez votre branche feature (`git checkout -b feature/AmazingFeature`)
3. Commitez vos changements (`git commit -m 'Add: Amazing Feature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📜 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👨‍💻 Auteur

**Baba Issa Ndiaye**
- GitHub: [@babaissandiaye](https://github.com/babaissandiaye)
- Email: babaissandiaye@example.com

---

⭐ **N'hésitez pas à donner une étoile si ce package vous aide !**