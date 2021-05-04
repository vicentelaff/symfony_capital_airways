# ANALYSE DU PROJET

Une cie de vols privés propose des trajets VIP vers des capitales européennes.
Une app avec:
    - Un système de login, avec 2 types d'utilisateurs (USER, ADMIN).
    - Un espace privé qui affiche les vols et propose des actions pour:
        * Créer un nouveau vol
        * Modifier un vol
        * Voir les vols
        * Supprimer des vols

L'application à ce stade permet de gérer les vols de la journée courante.

## Analyse fonctionnelle:
À faire...
- Compréhensible voir dicté par le client.
- Peut donner lieu à un Use Case UML. 

## Couche métier:
- Dégager les types de données.
- Ici:
    1. Vol || trajet
    2. Capitales
    3. User


## Modélisation DB:
- Un diagramme de classes UML basé sur l'analyse fonctionnelle.
- On va créer un diagramme MySQL WorkBench.
![DB_Diagramme](diagramme.jpg)

# CONFIGURATION DE L'APPLICATION
1. Database
2. Entités et relations
    * Ne pas faire User!
3. Fixtures:
    - Créer un tableau d'objets du type City
    - Créer un ou deux vols:
        * Numéro de vol statique, exemple: AH2349

__NB__: Éviter le copier-coller de code.

## Modifications:
### Entité Flight:
- Ajout d'un attribut `places`, qui représente, d'un côté administrateur, le nombre de places restantes dans l'avion.
- Nouvelles migrations.
- Modification des fixtures:
    * On va créer plusieurs vols (boucle).
    * On ajoute l'alimentation via un setter: setPlaces().

### Erreur `SQLSTATE[23502]: Not null violation: 7 ERROR:  column "seat" of relation "flight" contains null values`
Problème de Cache! Actions à effectuer:
- Effacer le cache avec `symfony console cache:clear`.
- Refaire la création de la base de données avec `symfony console doctrine:database:create`
- Refaire de nouvelles migrations.

## LA MAGIE:
L'application est générée automatiquement, du moins le coeur, avec `symfony console make:crud`.
Il reste à faire la personnalisation (bootstrap) pour les pages et le formulaire. Rajouter notre style au besoin.

## Gérer la classe FlightType:
- Tous les champs ne sont pas requis ?
- La relation avec City:
    * Même procédure que pour TodoList: Gérer les reference type (doc > form class relations entity).
- Ajouter les contraintes.

# CRÉER UNE CLASSE DE SERVICE
## Création:
- On crée une classe `App\Services\FlightService`.
- Ce service va permettre d'attribuer un numero de vol (aléatoire) lors de la création d'un vol.

## Utilisation dans les fixtures:
__NB__:

- On ne peut pas injecter directement dans la méthode load(). 
- Passer par un `__construct()`.
- On créé pour cela un attribut private.
- Puis, dans la méthode load():
```php
 $flight
    ->setFlightNumber($this->flightService->getFlightNumber())
```

# PROBLÈME DE TEMPLATE:
 - J'ai opté pour un choix qui pose problème.
 - J'ai délocalisé le bouton submit de la structure suivante :
 - La soumission ne s'effectue pas
```php
{{ form_start(form) }}
{{ form_widget(form) }}
// <button class="btn btn-outline-info">{{ button_label|default('Save') }}</button>
{{ form_end(form) }}
```

## Correction à étudier:
à faire.

# LA SÉCURITÉ
## L'entité User:
```bash
# En retour:
created: src/Entity/User.php
created: src/Repository/UserRepository.php
updated: src/Entity/User.php
updated: config/packages/security.yaml
```
## Update User:
On va ajouter un attribut `$firstname` et on refait une migration.
__NB__: La commande est `symfony console make:entity User`

## Migrations:
Voir dans la DB.
 * On ajoutera sûrement un champ supplémentaire pour username.

## Système Guard:
### Authentification et Autorisation:
1. L'authentification impose un contrôle des identifiants (email, password) appelés dans le jargon `credentials`.
2. L'autorisation est relative au rôle donné à un utilisateur qui limite ou pas l'utilisation de l'application.
```bash
 symfony console make:auth
 ## Réponse:
 created: src/Security/LoginFormAuthenticator.php
 updated: config/packages/security.yaml
 created: src/Controller/SecurityController.php
 created: templates/security/login.html.twig
```

## Test avec l'URL:
`http://localhost:8000/login`

## Fixtures:
On créé deux utilisateurs .
1. Admin a besoin que l'on injecte dans la méthode `__construct()` le service suivant:
```php
function __construct(FlightService $fs, UserPasswordEncoderInterface $passwordEncoder)
```

# NAVBAR
- 2 liens: Accueil et Logout.

# ROUTES ET SÉCURITÉ
Dans SecurityController, on applique une route par défaut.
Tout utilisateur arrive de ce fait par le formulaire.
```php
/**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
```
La classe appelée LoginFormAuthenticator gère ensuite l'accès à la page désirée dans la méthode `onAuthenticationSuccess()`
```php
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('flight_index'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
```

## Protection des routes et access:
Voir: https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html

### Dans FlightController et @IsGranted annotation:
On peut déjà commencer a filtrer les utilisateurs avec:
```php
/**
 * @IsGranted("ROLE_USER")
 */
class FlightController extends AbstractController
```
## Filtrer les autorisations dans les vues:
L'édition n'est accordée seulement à l'admin.
```php
<td>
	<a class="btn btn-outline-primary text-uppercase" href="{{ path('flight_show', {'id': flight.id}) }}">show</a>
	{% if is_granted("ROLE_ADMIN") %}
		<a class="btn btn-outline-warning text-uppercase" href="{{ path('flight_edit', {'id': flight.id}) }}">edit</a>
		<form method="post" action="{{ path('flight_delete', {'id': flight.id}) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
			<input type="hidden" name="_token" value="{{ csrf_token('delete' ~ flight.id) }}">
			<button class="btn btn-outline-danger text-uppercase">Delete</button>
		</form>
	{% endif %}
</td>
```

# PAGES D'ERREUR PERSONNALISÉES:
- Doc: https://symfony.com/doc/current/controller/error_pages.html
- Tester: Onne peut pas tester ces pages en mode dev, il faut passer l'URL directement, par ex. `http://localhost:8000/_error/404`.
