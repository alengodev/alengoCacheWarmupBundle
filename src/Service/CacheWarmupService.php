<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoCacheWarmupBundle\Service;

use EliasHaeussler\CacheWarmup\CacheWarmer;
use EliasHaeussler\CacheWarmup\Result\CacheWarmupResult;

class CacheWarmupService
{
    public function __construct(
    ) {
    }

    public function warmupCache($sitemap): CacheWarmupResult
    {
        $cacheWarmer = new CacheWarmer();
        $cacheWarmer->addSitemaps($sitemap);

        return $cacheWarmer->run();
    }
}
