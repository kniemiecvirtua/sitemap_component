<?php
declare(strict_types=1);

namespace Virtua\SitemapComponent\Controller;

use Snowdog\DevTest\Model\Session;

class SitemapFormAction
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function execute(): void
    {
        if (!$this->session->isLoggedIn()) {
            header("HTTP/1.0 403 Forbidden");
            require __DIR__ . '/../../../../src/view/403.phtml';
            exit;
        }

        require __DIR__ . '/../view/sitemap.phtml';
    }
}