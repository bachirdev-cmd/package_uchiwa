# App DAF Framework

Framework PHP moderne avec gestion des entités, migrations, seeders et controllers.

## Installation

```bash
composer require bachirdev/app-daff-framework
```

## Utilisation

### Configuration de base

```php
require_once 'vendor/autoload.php';

use AppDAF\Core\Application;

$app = new Application();
```

### Entités

```php
use AppDAF\Entity\BaseEntity;

class User extends BaseEntity 
{
    // Votre logique d'entité
}
```

### Controllers

```php
use AppDAF\Controller\BaseController;

class UserController extends BaseController 
{
    // Votre logique de controller
}
```

### Migrations

```php
use AppDAF\Migration\BaseMigration;

class CreateUsersTable extends BaseMigration 
{
    // Votre logique de migration
}
```

### Seeders

```php
use AppDAF\Seeders\BaseSeeder;

class UserSeeder extends BaseSeeder 
{
    // Votre logique de seeder
}
```

## Prérequis

- PHP >= 8.0
- Composer

## Dépendances

- cloudinary/cloudinary_php: Gestion des médias
- vlucas/phpdotenv: Gestion des variables d'environnement

## Licence

MIT

## Auteur

Bachir - [GitHub](https://github.com/bachirdev-cmd)
