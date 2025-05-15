## Requirements

* PHP 8.2
* Symfony >=6.0

### Install the bundle

Execute the following [composer](https://getcomposer.org/) command

```bash
composer require alengo/alengo-cache-warmup-bundle
```


### Enable the bundle

Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

 ```php
 return [
     /* ... */
     Alengo\Bundle\AlengoCacheWarmupBundle\AlengoCacheWarmupBundle::class => ['all' => true],
 ];
 ```

```bash
bin/console do:sch:up --force
```

### Configure the Bundle

config/packages/alengo_cache_warmup_bundle.yaml

```yaml
alengo_cache_warmup_bundle:
    messenger:
        routing:
            'Alengo\Bundle\AlengoCacheWarmupBundle\Message\SitemapCacheWarmup': async
```