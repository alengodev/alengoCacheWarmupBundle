## Requirements

* PHP 8.2
* Symfony >=7.0

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

config/packages/messages.yaml

```yaml
framework:
    messenger:
        transports:
            async:
                dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                options:
                    queue_name: 'async'
        routing:
            'Alengo\Bundle\AlengoCacheWarmupBundle\Message\SitemapCacheWarmup': async
```