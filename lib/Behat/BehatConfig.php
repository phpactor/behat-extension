<?php

namespace Phpactor\Extension\Behat\Behat;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class BehatConfig
{
    /**
     * @var Context[]
     */
    private $contexts = [];

    public function __construct(string $path)
    {
        $this->read($path);
    }

    private function read(string $path)
    {
        if (!file_exists($path)) {
            return;
        }

        $contents = Yaml::parseFile($path);
        $this->parseContexts($contents);
    }

    private function parseContexts(array $config)
    {
        foreach ($config as $profile) {
            if (!isset($profile['suites'])) {
                continue;
            }

            foreach ($profile['suites'] as $suiteName => $suite) {
                foreach ($suite['contexts'] as $context) {
                    $this->contexts[] = new Context($suiteName, $context);
                }
            }
        }
    }

    /**
     * @return Context[]
     */
    public function contexts(): array
    {
        return $this->contexts;
    }
}
