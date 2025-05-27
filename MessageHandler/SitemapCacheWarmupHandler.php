<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoCacheWarmupBundle\MessageHandler;

use Alengo\Bundle\AlengoCacheWarmupBundle\Message\SitemapCacheWarmup;
use Alengo\Bundle\AlengoCacheWarmupBundle\Service\CacheWarmupService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class SitemapCacheWarmupHandler
{
    public function __construct(
        private readonly CacheWarmupService $cacheWarmupService,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly string $defaultSenderName,
        private readonly string $defaultSenderMail,
        private readonly string $adminEmail,
        private readonly string $notification,
    ) {
    }

    public function __invoke(SitemapCacheWarmup $sitemap): void
    {
        $result = $this->cacheWarmupService->warmupCache($sitemap->getSitemap());

        if ('email' === $this->notification) {
            // Get successful and failed URLs
            $successfulUrls = $result->getSuccessful();
            $failedUrls = $result->getFailed();
            $totalUrls = \count($successfulUrls) + \count($failedUrls);

            $email = (new Email())
                ->from(new Address($this->defaultSenderMail, $this->defaultSenderName))
                ->to(new Address($this->adminEmail))
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

            try {
                $this->mailer->send($email);
            } catch (\Throwable $e) {
                $this->logger->error('Error sending Email: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }
    }
}
