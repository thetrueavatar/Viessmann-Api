# Siren

A serialiser and parser for [Siren](https://github.com/kevinswiber/siren) APIs.

## Siren

Siren is a schema for HATEOAS APIs which uses JSON.

## Current State

This project's releases strongly adhere to SemVer. At this point in time, this
project is in zero point state. While it's functionality is sound and working,
large backwards compatibility breaks can be expected in new releases.

## Installing

```
composer require tomphp/siren:dev-master
```

## Serialising

Creating an entity is done using the builder; this is created by calling
`TomPHP\Siren\Entity::builder()`.

```php
use TomPHP\Siren\{Entity, Action};

$editAction = Action::builder()
    ->setName('edit')
    ->setTitle('Edit User')
    ->setHref('http://example.com/api/v1/users/ea019642-9c53-415f-88b6-e191dea184f9')
    ->setMethod('PUT')
    ->setType('application/vnd.siren+json')
    ->addField('email', ['email-class'], 'email', 'test@example.com', 'Email Address')
    ->build();

$user = Entity::builder()
    ->addLink('self', 'http://example.com/api/v1/users/ea019642-9c53-415f-88b6-e191dea184f9')
    ->addProperty('full_name', 'Tom Oram')
    ->addProperty('email', 'tom@example.com')
    ->addClass('item')
    ->addAction($editAction)
    ->build();

print(json_encode($user->toArray());
```

## Parsing

An entity can be created from a JSON decoded array by using `fromArray()`
constructor.

```php
// Assuming the JSON from the serialising example.

$user = Entity::fromArray(json_decode($json, true));

echo 'Name: ' . $user->getProperty('full_name') . PHP_EOL;
echo 'Email: ' . $user->getProperty('email') . PHP_EOL;

$editAction = $user->getAction('edit');
echo 'Edit Action ' . $editAction->getMehod() . ' ' . $editAction->getHref() . PHP_EOL;
```

## Contributing

I want to get this project stable as soon as possible, so any help I can get
is greatly appreciated. If you think you can help, please submit a Pull Request.
