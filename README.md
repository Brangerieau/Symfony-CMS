# Symfony CMS

## 🔨 Installation

### Get the bundle using composer

The best way to install this bundle is to rely on [Composer](https://getcomposer.org/):

```bash
$ composer require brangerieau/symfonycms
```

### Enable the bundle

Register the bundle in your application's kernel:

```php
// config/bundles.php
<?php

return [
    /* ... */
    Brangerieau\SymfonyCmsBundle\SymfonyCmsBundle::class => ['all' => true],
];

```

### Configuration

#### Routes

You must activate the custom routes of the bundle to be able to access the administration:

```yaml
# config/routes.yaml
symfony_cms:
    resource: '@SymfonyCmsBundle/config/routes.yaml'
```

#### Assets

Activate assets to have a stylized on admin :

```bash
$ php bin/console assets:install
```

TODO : security.yaml 
-

## ➕ Optional

### Fixtures

If you want to add the fixtures from the bundle:

```bash
$ composer require --dev orm-fixtures
$ composer require --dev fakerphp/faker

# Load all fixtures :
$ php bin/console doctrine:fixtures:load
```

## ✍️ Authors

Symfony CMS was originally created by [Brangerieau Thibaud](https://www.brangerieau-thibaud.fr).