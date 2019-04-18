<?php
declare(strict_types=1);

namespace Virtua\SitemapComponent\Controller;

use Snowdog\DevTest\Model\UserManager;
use Virtua\SitemapComponent\Component\Sitemap;
use Virtua\SitemapComponent\Model\SitemapManager;

class ImportSitemapAction
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

    public function execute():void
    {
        if (isset($_SESSION['login'])) {
            $user = $this->userManager->getByLogin($_SESSION['login']);

            $file = $_POST['file'];
            $websiteName = $_POST['website_name'];
            try {
                $parsedData = $this->sitemapComponent->parse($file);
                $this->sitemapManager->import($parsedData, $user, $websiteName);
                $_SESSION['flash'] = 'Sitemap import has been successfully finished.';
            } catch (\Exception $exception) {
                $_SESSION['flash'] = $exception->getMessage();
            }
        }

        header('Location: /');
    }
}