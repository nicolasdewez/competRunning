<?php

namespace App\Command;

use App\Model\Search;
use App\Service\ConfigLoader;
use App\Service\CountTrials;
use App\Service\DistanceToKm;
use App\Service\Export;
use App\Service\GetCompetition;
use App\Service\GetResults;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GetCompetitionsCommand extends Command
{
    const COUNT_ELEMENTS = 50;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:competitions:get')
            ->setDescription('Get competitions')
            ->addOption('startDate', 's', InputOption::VALUE_REQUIRED)
            ->addOption('endDate', 'e', InputOption::VALUE_REQUIRED)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('%s : Début du traitement.', (new \DateTime())->format('d/m/Y H:i:s')));

        $startDate = $this->getDate($input, $output, 'startDate', 'début');
        $endDate = $this->getDate($input, $output, 'endDate', 'fin');

        $search = (new ConfigLoader())->createSearch();
        $search
            ->setStartDate(null !== $startDate ? new \DateTime($startDate) : null)
            ->setEndDate(null !== $endDate ? new \DateTime($endDate) : null)
        ;

        // Get competitions
        $competitions = [];
        $getCompetition = new GetCompetition(new DistanceToKm());

        $results = (new GetResults())->execute($search);
        $output->writeln(sprintf(
            '<info>%d compétition(s) trouvée(s) (Filtres sur distance non pris en compte).</info>',
            count($results)
        ));

        // Get trials and details of competitions
        foreach ($results as $key => $result) {
            $competition = $getCompetition->execute($search, $result[1], $result[0]);

            if (0 !== $key && 0 === ($key % self::COUNT_ELEMENTS)) {
                $output->writeln(sprintf('<info>%d compétitions traitées.</info>', $key));
            }

            if (null === $competition) {
                continue;
            }

            $competitions[] = $competition;
        }

        $countCompetitions = count($competitions);
        $countTrials = (new CountTrials())->execute($competitions);

        $output->writeln(sprintf(
            '<info>%d course(s) trouvée(s), %d distance(s) au total.</info>',
            $countCompetitions,
            $countTrials
        ));

        if (0 === $countTrials) {
            $output->writeln(sprintf('%s : Fin du traitement.', (new \DateTime())->format('d/m/Y H:i:s')));
            return;
        }

        (new Export())->execute($competitions);

        $output->writeln(sprintf('%s : Fin du traitement.', (new \DateTime())->format('d/m/Y H:i:s')));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $titleOption
     * @param string          $titleDate
     *
     * @return string|null
     */
    private function getDate(InputInterface $input, OutputInterface $output, string $titleOption, string $titleDate): ?string
    {
        if (null !== $input->getOption($titleOption)) {
            return $input->getOption($titleOption);
        }

        $helper = $this->getHelper('question');

        $question = new Question(sprintf('Entrer la date de %s (aaaa-mm-jj) : ', $titleDate), null);

        return $helper->ask($input, $output, $question);
    }
}
