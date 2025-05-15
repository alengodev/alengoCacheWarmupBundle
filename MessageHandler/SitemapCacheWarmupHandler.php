<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoCacheWarmupBundle\MessageHandler;

use Alengo\Bundle\AlengoCacheWarmupBundle\Message\SitemapCacheWarmup;
use Alengo\Bundle\AlengoCacheWarmupBundle\Service\CacheWarmupService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class SitemapCacheWarmupHandler
{
    public function __construct(
        private readonly ?string $defaultSenderName = null,
        private readonly ?string $defaultSenderMail = null,
        private readonly ?string $adminEmail = null,
        private readonly CacheWarmupService $cacheWarmupService,
        private readonly MailerInterface $mailer,
    ) {
    }

    public function __invoke(SitemapCacheWarmup $sitemap): void
    {
        $result = $this->cacheWarmupService->warmupCache($sitemap->getSitemap());

        // Get successful and failed URLs
        $successfulUrls = $result->getSuccessful();
        $failedUrls = $result->getFailed();
        $totalUrls = \count($successfulUrls) + \count($failedUrls);

        $email = (new Email())
            ->from($this->defaultSenderMail, $this->defaultSenderName)
            ->to($this->adminEmail)
            ->subject('Cache warmed up')
            ->text(
                \sprintf(
                    "Sitemap: %s\nWebspace: %s\nWebspaceKey: %s\n\nCache cleared for %d URLs.\n%d URLs were successful, %d URLs failed.",
                    $sitemap->getSitemap(),
                    $sitemap->getWebspaceName(),
                    $sitemap->getWebspaceKey(),
                    $totalUrls,
                    \count($successfulUrls),
                    \count($failedUrls),
                ),
            );

        $this->mailer->send($email);
    }
}
