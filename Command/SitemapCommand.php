<?php
declare(strict_types=1);

namespace Virtua\SitemapComponent\Command;

use Snowdog\DevTest\Model\UserManager;
use Virtua\SitemapComponent\Component\Sitemap;
use Virtua\SitemapComponent\Model\SitemapManager;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Sitemap
     */
    private $sitemapComponent;

    /**
     * @var SitemapManager
     */
    private $sitemapManager;

    public function __construct(
        UserManager $userManager,
        Sitemap $sitemapComponent,
        SitemapManager $sitemapManager
    ) {
        $this->userManager = $userManager;
        $this->sitemapComponent = $sitemapComponent;
        $this->sitemapManager = $sitemapManager;
    }

    public function __invoke(string $path, string $website_name, int $user_id, OutputInterface $output): void
    {
        $user = $this->userManager->get($user_id);
        if (!$user) {
            $output->writeln('<error>User with ID' . $user_id . ' does not exist!</error>');
            return;
        }

        try {
            $parsedData = $this->sitemapComponent->parse($path);
            $messages = $this->sitemapManager->import($parsedData, $user, $website_name);
        } catch (\Exception $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
            return;
        }

        foreach ($messages as $message) {
            $output->writeln($message);
        }
    }
}
