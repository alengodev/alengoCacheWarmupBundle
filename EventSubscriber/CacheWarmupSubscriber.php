<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoCacheWarmupBundle\EventSubscriber;

use Alengo\Bundle\AlengoCacheWarmupBundle\Message\SitemapCacheWarmup;
use Sulu\Bundle\WebsiteBundle\Domain\Event\CacheClearedEvent;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CacheWarmupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly WebspaceManagerInterface $webspaceManager,
        private readonly MessageBusInterface $bus,
        private readonly KernelInterface $kernel,
        private readonly bool $allowCacheWarmup = true,
        private readonly array $allowedWebspaces = [],
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CacheClearedEvent::class => 'warmupCache',
        ];
    }

    public function warmupCache(CacheClearedEvent $event): void
    {
        if (false === $this->allowCacheWarmup || !\in_array($event->getResourceWebspaceKey(), $this->allowedWebspaces, true)) {
            return;
        }

        $portalInformation = $this->webspaceManager->getPortalInformationsByWebspaceKey($this->kernel->getEnvironment(), $event->getResourceWebspaceKey());
        $webspaceName = null;
        $webspaceKey = null;
        $sitemap = null;

        foreach ($portalInformation as $item) {
            if (null === $item->getLocalization() && 2 === $item->getType()) {
                $webspaceName = $item->getPortal()->getName();
                $webspaceKey = $item->getWebspaceKey();
                $sitemap = 'https://' . $item->getUrl() . '/sitemap.xml';
                break;
            }
        }
        if ($sitemap) {
            $this->bus->dispatch(new SitemapCacheWarmup(
                webspaceName: $webspaceName,
                webspaceKey: $webspaceKey,
                sitemap: $sitemap,
            ));
        }
    }
}
