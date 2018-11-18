<?php

namespace Phpactor\Extension\Behat\Completor;

use Generator;
use Phpactor\Completion\Core\Completor;
use Phpactor\Completion\Core\Suggestion;
use Phpactor\Extension\Behat\Behat\Step;
use Phpactor\Extension\Behat\Behat\StepFactory;
use Phpactor\Extension\Behat\Behat\StepGenerator;

class FeatureStepCompletor implements Completor
{
    /**
     * @var StepGenerator
     */
    private $generator;

    public function __construct(StepGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritDoc}
     */
    public function complete(string $source, int $byteOffset): Generator
    {
        /** @var Step $step */
        foreach ($this->generator as $step) {
            yield Suggestion::createWithOptions($step->pattern(), [
                'short_description' => $step->context()->class(),
                'type' => Suggestion::TYPE_SNIPPET,
            ]);
        }
    }
}
