<?php
declare(strict_types=1);

namespace Virtua\SitemapComponent\Component;

use Exception;
use SimpleXMLElement;

class Sitemap
{
    const SITEMAP_IMPORT_DIR = 'var/import/';

    /**
     * @param string $file
     * @return array
     * @throws \Exception
     */
    public function parse(string $file): array
    {
        $file = $this->getFullPath($file);
        if (!file_exists($file)) {
            throw new Exception('File does not exist');
        }

        $content = $this->getContent($file);
        $xml = new SimpleXMLElement($content);

        return $this->parseXmlToArray($xml);
    }

    /**
     * @param string $file
     * @return string
     */
    private function getFullPath(string $file): string
    {
        return __DIR__ . '/../../' . self::SITEMAP_IMPORT_DIR . $file;
    }

    /**
     * @param string $xml
     * @return array
     */
    private function parseXmlToArray(SimpleXMLElement $xml): array
    {
        $data = [];
        foreach ($xml->url as $node) {
            $url = (string)$node->loc;
            if (!$url) {
                continue;
            }

            $urlParts = parse_url($url);
            $hostname = (!empty($urlParts['host'])) ? $urlParts['host'] : null;
            if (null === $hostname) {
                continue;
            }

            $url = (!empty($urlParts['path'])) ? ltrim($urlParts['path'], DIRECTORY_SEPARATOR) : null;
            if (null === $url) {
                continue;
            }

            $data[$hostname][] = $url;
        }

        return $data;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getContent(string $file): string
    {
        return (string)file_get_contents($file);
    }
}
