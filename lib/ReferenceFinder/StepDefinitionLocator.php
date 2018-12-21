<?php

namespace Phpactor\Extension\Behat\ReferenceFinder;

use Phpactor\Extension\Behat\Behat\Step;
use Phpactor\Extension\Behat\Behat\StepGenerator;
use Phpactor\Extension\Behat\Behat\StepParser;
use Phpactor\ReferenceFinder\DefinitionLocation;
use Phpactor\ReferenceFinder\DefinitionLocator;
use Phpactor\ReferenceFinder\Exception\CouldNotLocateDefinition;
use Phpactor\ReferenceFinder\Exception\UnsupportedDocument;
use Phpactor\TextDocument\ByteOffset;
use Phpactor\TextDocument\TextDocument;
use Phpactor\TextDocument\TextDocumentUri;
use Phpactor\TextDocument\Util\LineAtOffset;

class StepDefinitionLocator implements DefinitionLocator
{
    /**
     * @var StepGenerator
     */
    private $generator;

    /**
     * @var StepParser
     */
    private $parser;

    public function __construct(StepGenerator $generator, StepParser $parser)
    {
        $this->generator = $generator;
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    public function locateDefinition(TextDocument $document, ByteOffset $byteOffset): DefinitionLocation
    {
        if (!$document->language()->in(['cucumber', 'behat', 'gherkin'])) {
            throw new UnsupportedDocument(sprintf('Language must be one of cucumber, behat or gherkin'));
        }

        $line = (new LineAtOffset())($document->__toString(), $byteOffset->toInt());
        $stepLines = $this->parser->parseSteps($line);

        if (empty($stepLines)) {
            throw new CouldNotLocateDefinition(sprintf('Could not parse step line: "%s"', $line));
        }

        $line = reset($stepLines);

        $step = $this->findStep($line);

        return new DefinitionLocation(TextDocumentUri::fromString($step->path()), ByteOffset::fromInt($step->byteOffset()));
    }

    private function findStep($line): Step
    {
        foreach ($this->generator as $step) {
            if ($step->matches($line)) {
                return $step;
            }
        }

        throw new CouldNotLocateDefinition(sprintf('Could not match step for: "%s"', $line));
    }
}
