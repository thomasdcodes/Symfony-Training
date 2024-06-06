<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Crawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CrawlerCrawlCommand extends Command
{
    public function __construct(protected readonly Crawler $crawler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('crawler:crawl')
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->crawler->crawl();

        $results = $this->crawler->getResults();

        foreach ($results as $result) {
            $io->writeln($result);
        }

        return Command::SUCCESS;
    }
}
