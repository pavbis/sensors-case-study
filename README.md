# sensors/case-study

## Description

A sensors case study

## What's in?

* PHP 7.4
* Symfony5
* Nginx
* RDBMS (PostgreSQL)

## Local development

### Bring up the environment
Feel free to use the provided Makefile to run the whole project. It costs you only 2 clicks (in case you are using Makefile for PHPStorm).

```bash
make up
```

```bash
make seed-database
```

This will automatically:
* Build all necessary docker images for `nginx, php, composer and RDBMS`
* The web server exposed on port 8080

----------------

```bash
make qa
```

This will automatically:
* Run the static code analyzer (PHPStan)
* Run unit and integration tests