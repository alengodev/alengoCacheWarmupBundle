## Requirements

* PHP 8.2
* Symfony >=7.2

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

### Configure the Cache Warmup

config/packages/alengo_cache_warmup.yaml

```yaml
alengo_cache_warmup:
    enabled: true
    allowed_webspaces:
        - 'webspace1'
        - 'webspace2'
    notification: 'email' # 'email' or 'none'
```

.env variables to get an email notification when the cache warmup is done

```dotenv
    ADMIN_EMAIL=admin@sulu.rocks
    DEFAULT_SENDER_NAME="Sulu // Notify"
    DEFAULT_SENDER_MAIL=email@localhost
```

Be sure you have installed the [Symfony Messenger](https://symfony.com/doc/current/messenger.html) and configured it properly.