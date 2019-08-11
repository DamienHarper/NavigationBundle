Running Tests
=============

Before running the test suite, you first need to install dev dependencies:

```bash
composer install --dev
```

Then you can run the test suite:

```bash
composer test
```

Rename the phpunit.xml.dist file to phpunit.xml, then add your own API keys:

```xml
<php>
    <env name="HERE_APP_ID" value="YOUR_APP_ID" />
    <env name="HERE_APP_CODE" value="YOUR_APP_CODE" />
    <env name="GOOGLE_MAPS_API_KEY" value="YOUR_API_KEY" />
</php>
```
You're done.

**Note:** code coverage report is generated in `tests/coverage` folder (requires [Xdebug extension](https://xdebug.org/docs/install#configure-php)).
