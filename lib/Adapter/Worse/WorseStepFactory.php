<?php

namespace Phpactor\Extension\Behat\Adapter\Worse;

use Generator;
use Phpactor\Extension\Behat\Behat\Context;
use Phpactor\Extension\Behat\Behat\Step;
use Phpactor\Extension\Behat\Behat\StepFactory;
use Phpactor\Extension\Behat\Behat\StepParser;
use Phpactor\WorseReflection\Core\Reflection\ReflectionMethod;
use Phpactor\WorseReflection\Core\Reflector\ClassReflector;

class WorseStepFactory implements StepFactory
{
    /**
     * @var ClassReflector
     */
    private $reflector;

    public function __construct(ClassReflector $reflector)
    {
        $this->reflector = $reflector;
    }

    /**
     * @param Context[] $contexts
     */
    public function generate(StepParser $parser, array $contexts): Generator
    {
        foreach ($contexts as $context) {
            $class = $this->reflector->reflectClass($context->class());

            /** @var ReflectionMethod $method */
            foreach ($class->methods() as $method) {
                $steps = $parser->parseSteps($method->docblock()->raw());

                if (!$steps) {
                    continue;
                }

                foreach ($steps as $step) {
                    yield new Step(
                        $context,
                        $method->name(),
                        $step,
                        $class->sourceCode()->path(),
                        $method->position()->fullStart()
                    );
                }
            }
        }
    }
}
