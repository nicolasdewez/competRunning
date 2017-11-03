<?php

namespace App\Command;

use App\Model\Search;
use App\Service\ConfigLoader;
use App\Service\CountTrials;
use App\Service\Export;
use App\Service\GetCompetition;
use App\Service\GetResults;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GetCompetitionsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:competitions:get')
            ->setDescription('Get competitions')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionStart = new Question('Entrer la date de début (aaaa-mm-jj) : ', null);
        $startDate = $helper->ask($input, $output, $questionStart);

        $questionEnd = new Question('Entrer la date de fin (aaaa-mm-jj) : ', null);
        $endDate = $helper->ask($input, $output, $questionEnd);

        $search = (new ConfigLoader())->createSearch();
        $search
            ->setStartDate(null !== $startDate ? new \DateTime($startDate) : null)
            ->setEndDate(null !== $endDate ? new \DateTime($endDate) : null)
        ;

        // Get competitions
        $competitions = [];
        $getCompetition = new GetCompetition();

        $results = (new GetResults())->execute($search);
        $output->writeln(sprintf('<info>%d compétition(s) trouvée(s).</info>', count($results)));

        // Get trials and details of competitions
        foreach ($results as $key => $result) {
            $competition = $getCompetition->execute($result[1], $result[0]);

            if (0 === ($key % 50)) {
                $output->writeln(sprintf('<info>%d compétitions traitées.</info>', $key));
            }
            
            if (null === $competition) {
                continue;
            }

            $competitions[] = $competition;
        }

        $count = (new CountTrials())->execute($competitions);
        $output->writeln(sprintf('<info>%d course(s) trouvée(s).</info>', $count));

        if (0 === $count) {
            return;
        }

        (new Export())->execute($competitions);
    }
}
