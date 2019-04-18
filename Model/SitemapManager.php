<?php
declare(strict_types=1);

namespace Virtua\SitemapComponent\Model;

class SitemapManager
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        WebsiteManager $websiteManager,
        PageManager $pageManager,
        UserManager $userManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->userManager = $userManager;
    }

    /**
     * @param array $data
     * @param User $user
     * @param string $websiteName
     * @return array
     */
    public function import(array $data, User $user, string $websiteName): array
    {
        $messages = [];

        foreach ($data as $hostname => $urls) {
            $website = $this->websiteManager->getWebsiteByUserAndHost($user, $hostname);
            if (!$website) {
                $websiteId = $this->websiteManager->create($user, $websiteName, $hostname);
                if ($websiteId) {
                    $website = $this->websiteManager->getById($websiteId);
                }
            }

            if (!$website) {
                continue;
            }

            foreach ($urls as $url) {
                $websiteId = $website->getWebsiteId();
                $page = $this->pageManager->getPageByWebsiteAndUrl($websiteId, $url);
                if (!$page) {
                    if ($pageId = $this->pageManager->create($website, $url)) {
                        $messages[] = 'Page ' . $pageId . ' has been created';
                    }
                }
            }
        }

        return $messages;
    }
}
