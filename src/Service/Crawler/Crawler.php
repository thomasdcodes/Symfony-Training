<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use App\Repository\CrawlingRepository;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Crawler
{
    protected array $results = [];

    public function __construct(
        protected readonly CrawlingRepository $crawlingRepository,
        protected readonly HttpClientInterface $httpClient,
    )
    {
    }

    public function crawl(): void
    {
        //1. Lade alle Crawlings
        $crawlings = $this->crawlingRepository->findAll();

        foreach ($crawlings as $crawling) {
            $url = sprintf('%s%s', $crawling->getServerInstance()->getBaseUrl(), $crawling->getTarget());

            //3. Führe den http-Request durch
            try {
                $response = $this->httpClient->request('GET', $url);
                $this->results[] = sprintf('%s: %d', $url, $response->getStatusCode());
            } catch (TransportExceptionInterface $e) {
                print_r($e->getMessage());
            }
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }
}