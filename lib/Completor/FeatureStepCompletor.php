<?php

namespace Phpactor\Extension\Behat\Completor;

use Generator;
use Phpactor\Completion\Core\Completor;
use Phpactor\Completion\Core\Suggestion;
use Phpactor\Extension\Behat\Behat\Step;
use Phpactor\Extension\Behat\Behat\StepFactory;
use Phpactor\Extension\Behat\Behat\StepGenerator;
use Phpactor\Extension\Behat\Behat\StepParser;
use Phpactor\Extension\Behat\Behat\StepScorer;

class FeatureStepCompletor implements Completor
{
    /**
     * @var StepGenerator
     */
    private $generator;

    /**
     * @var StepParser
     */
    private $parser;

    /**
     * @var StepScorer
     */
    private $stepSorter;

    public function __construct(StepGenerator $generator, StepParser $parser, StepScorer $stepSorter = null)
    {
        $this->generator = $generator;
        $this->parser = $parser;
        $this->stepSorter = $stepSorter ?: new StepScorer();
    }

    /**
     * {@inheritDoc}
     */
    public function complete(string $source, int $byteOffset): Generator
    {
        $currentLine = $this->lineForOffset($source, $byteOffset);
        $parsed = $this->parser->parseSteps($currentLine);

        if (empty($parsed)) {
            return;
        }
        $partial = $parsed[0];

        $steps = iterator_to_array($this->generator);

        if ($partial) {
            $scores = $this->stepSorter->scoreSteps($steps, $partial);
            usort($steps, function (Step $step1, Step $step2) use ($scores) {
                return $scores[$step2->pattern()] <=> $scores[$step1->pattern()];
            });
        }



        /** @var Step $step */
        foreach ($steps as $step) {
            $suggestion = $step->pattern();

            if (preg_match('{^' . $partial. '}i', $suggestion)) {
                $suggestion = substr($suggestion, strlen($partial));
            }

            yield Suggestion::createWithOptions($suggestion, [
                'label' => $step->pattern(),
                'short_description' => $step->context()->class(),
                'type' => Suggestion::TYPE_SNIPPET,
            ]);
        }
    }

    private function lineForOffset(string $source, int $byteOffset): string
    {
        $length = 0;
        $last = '';
        foreach (preg_split('/$(\R?^)/m', $source, null, PREG_SPLIT_OFFSET_CAPTURE) as $line) {
            [ $line, $offset] = $line;

            if ($offset + strlen($line) >= $byteOffset) {
                return $line;
            }
        }

        return '';
    }
}
