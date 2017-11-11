<?php

namespace App\Service;

use App\Configuration\AppConfiguration;
use App\Model\Search;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    const CONFIG_FILE = 'app.yaml';

    /**
     * @return Search
     */
    public function createSearch(): Search
    {
        $config = $this->getProcessedConfiguration();

        $search = (new Search())
            ->setYear($config['year'])
            ->setLigue($config['ligue'])
            ->setType($config['type'])
            ->setDepartment($config['department'])
            ->setChallenge($config['challenge'])
            ->setDistanceMin($config['distance']['min'])
            ->setDistanceMax($config['distance']['max'])
        ;

        return $search;
    }

    /**
     * @return string
     */
    private function getConfigPath(): string
    {
        return sprintf('%s/configuration/%s', dirname(dirname(__DIR__)), self::CONFIG_FILE);
    }

    /**
     * @return array
     */
    private function getProcessedConfiguration(): array
    {
        $config = Yaml::parse(file_get_contents($this->getConfigPath()));

        return (new Processor())->processConfiguration(
            new AppConfiguration(),
            $config
        );
    }
}
