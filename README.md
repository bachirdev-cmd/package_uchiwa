# AppDAF - Framework PHP Modulaire

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-blue.svg)](https://php.net)

Framework PHP modulaire pour développer des applications de gestion citoyenne avec une architecture propre basée sur l'injection de dépendances et le pattern Repository.

## 🚀 Fonctionnalités

- **Architecture modulaire** : Organisation claire avec séparation des responsabilités
- **Injection de dépendances** : Système DI avec configuration YAML
- **Pattern Repository** : Abstraction de la couche d'accès aux données
- **Entities & Services** : Couches métier bien définies
- **Singleton Pattern** : Gestion optimisée des instances
- **Router intégré** : Système de routage flexible
- **Gestion d'erreurs** : Controller d'erreurs centralisé

## 📦 Installation

```bash
composer require diame/appdaf
```

## 🛠️ Structure

```
src/
├── controller/     # Contrôleurs de l'application
├── entity/         # Entités métier
├── enum/           # Énumérations et constantes
├── repository/     # Couche d'accès aux données
└── service/        # Services métier

app/
├── core/           # Noyau du framework
│   ├── App.php           # Container DI principal
│   ├── Database.php      # Gestionnaire base de données
│   ├── Router.php        # Système de routage
│   └── Singleton.php     # Pattern Singleton
└── config/         # Configuration
```

## 💻 Usage

### Configuration des services

Créez un fichier `app/config/services.yml` :

```yaml
CitoyenRepository: AppDAF\Repository\CitoyenRepository
CitoyenService: AppDAF\Service\CitoyenService
```

### Utilisation du container DI

```php
use AppDAF\Core\App;
use AppDAF\Enum\ClassName;

// Récupération d'un service
$citoyenService = App::getDependencie(ClassName::CITOYEN_SERVICE);
```

### Création d'une entité

```php
use AppDAF\Entity\CitoyenEntity;

$citoyen = new CitoyenEntity(
    "Diame",
    "Pabass",
    "1234567890123",
    "1990-01-01",
    "Dakar",
    "verso_url",
    "recto_url"
);
```

### Service layer

```php
use AppDAF\Service\CitoyenService;

class CitoyenService extends Singleton
{
    public function get_by_cni(string $cni): ?CitoyenEntity
    {
        return $this->citoyenRepository->selectByCni($cni);
    }
}
```

## 🔧 Développement

### Prérequis

- PHP >= 8.0
- Composer
- Extension YAML PHP

### Installation dev

```bash
git clone https://github.com/bachirdev-cmd/packages.git
cd packages
composer install
```

### Tests

```bash
composer test
```

### Serveur de développement

```bash
composer start
```

## 📝 Architecture

Le framework suit une architecture en couches :

1. **Controllers** : Gestion des requêtes HTTP
2. **Services** : Logique métier
3. **Repositories** : Accès aux données
4. **Entities** : Modèles de données
5. **Core** : Noyau du framework (DI, Router, etc.)

## 🤝 Contribution

Les contributions sont les bienvenues ! Merci de :

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/amazing-feature`)
3. Commit vos changements (`git commit -m 'Add amazing feature'`)
4. Push sur la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request

## 📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## ✨ Auteur

**Pabass Diamé**
- Email: pabassdiame76@gmail.com
- GitHub: [@bachirdev-cmd](https://github.com/bachirdev-cmd)
