<?php

namespace Phpactor\Extension\Behat\Behat;

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
        $paths = [
            $path,
            $path . '.dist'
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            $contents = Yaml::parseFile($path);
            $this->parseContexts($contents);
        }
    }

    private function parseContexts(array $config)
    {
        foreach ($config as $profile) {
            if (!isset($profile['suites'])) {
                continue;
            }

            foreach ($profile['suites'] as $suiteName => $suite) {
                if (!isset($suite['contexts'])) {
                    continue;
                }
                foreach ($suite['contexts'] as $key => $context) {
                    // note this isn't tested
                    if (is_array($context)) {
                        $context = key($context);
                    }

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
