Running Tests
=============

Before running the test suite, you first need to install dev dependencies:

```bash
composer install --dev
```

Then you can run the test suite:

### Default configuration

This configuration generates code coverage report in `tests/coverage` folder (requires [Xdebug extension](https://xdebug.org/docs/install#configure-php)).

```bash
./vendor/bin/phpunit 
```