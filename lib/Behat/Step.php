<?php

namespace Phpactor\Extension\Behat\Behat;

class Step
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $pattern;

    public function __construct(Context $context, string $method, string $pattern)
    {
        $this->context = $context;
        $this->method = $method;
        $this->pattern = $pattern;
    }

    public function context(): Context
    {
        return $this->context;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function pattern(): string
    {
        return $this->pattern;
    }
}
