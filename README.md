# PHP Data Transfer Objects (DTOs)
A simple PHP DTO library.

[![PHP version](https://badge.fury.io/ph/drewkimberly%2Fphp_dtos.svg)](https://badge.fury.io/ph/drewkimberly%2Fphp_dtos)
[![Build Status](https://travis-ci.org/Drew-Kimberly/php_dtos.svg?branch=master)](https://travis-ci.org/Drew-Kimberly/php_dtos)
[![Coverage Status](https://coveralls.io/repos/github/Drew-Kimberly/php_dtos/badge.svg?branch=master)](https://coveralls.io/github/Drew-Kimberly/php_dtos?branch=master)

### Installation:
```bash
composer require drewkimberly/php_dtos
```

### Usage:
This library aims to make it easy to create Data Transfer Objects (DTOs). 
DTOs are simple encapsulations of data, independent of application or domain-specific logic. 
They are serializable objects, making them a clean way to represent data in the transfer layer 
of an application (i.e. REST API payloads).

The following table highlights the main classes introduced by this library:

| Class | Responsibility |
|-------|----------------|
| `\php_dtos\Dto\DtoBase`   | Subclass of all DTOs implemented in an application.              |
| `\php_dtos\Dto\DtoCollection`     | Represents a DTO collection in an application. In rare cases this class can be subclassed, but the typical usecase should look something like: `new DtoCollection(MyDto::class);`, where the collection is instantiated with a reference to the underlying (or collected) DTO instance.   |
| `\php_dtos\Json\JsonReader`     | Helper class for safely parsing incoming JSON.         |


For more information on Data Transfer Objects, see:
- https://martinfowler.com/eaaCatalog/dataTransferObject.html
- https://en.wikipedia.org/wiki/Data_transfer_object

### Development

#### Setup
1. Fork this repository
1. Clone your forked repository
1. From the root of your cloned repository run: `composer install`


#### Testing
[TravisCI](https://travis-ci.org/) is used for CI/CD.
The following validations are performed against every Pull Request:
1. [PHPLint](https://github.com/overtrue/phplint)
1. [PHP Codesniffer (PSR2 Sniff)](https://github.com/squizlabs/PHP_CodeSniffer)
1. [PHPUnit](https://phpunit.de/)


#### Deployment
When a tag is cut, TravisCI will deploy the package to packagist.


#### Contributing
1. Open an Issue on GitHub.
1. Open a Pull Request.
    - Make sure the [TravisCI](https://travis-ci.org/Drew-Kimberly/php_dtos) build is passing for your PR.
    - Request a Code Review on your PR.
