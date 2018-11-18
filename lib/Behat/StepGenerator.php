<?php

namespace Phpactor\Extension\Behat\Behat;

use IteratorAggregate;

class StepGenerator implements IteratorAggregate
{
    /**
     * @var BehatConfig
     */
    private $config;

    /**
     * @var StepParser
     */
    private $parser;

    /**
     * @var StepFactory
     */
    private $factory;

    public function __construct(BehatConfig $config, StepFactory $factory, StepParser $parser)
    {
        $this->config = $config;
        $this->parser = $parser;
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        yield from $this->factory->generate($this->parser, $this->config->contexts());
    }
}
