<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoCacheWarmupBundle\Message;

class SitemapCacheWarmup
{
    public function __construct(
        private readonly string $webspaceName,
        private readonly string $webspaceKey,
        private readonly string $sitemap,
    ) {
    }

    public function getWebspaceName(): string
    {
        return $this->webspaceName;
    }

    public function getWebspaceKey(): string
    {
        return $this->webspaceKey;
    }

    public function getSitemap(): string
    {
        return $this->sitemap;
    }
}
