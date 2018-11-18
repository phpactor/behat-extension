<?php

namespace Phpactor\Extension\Behat\Tests\Integration\Adapter\Worse;

use PHPUnit\Framework\TestCase;
use Phpactor\Extension\Behat\Adapter\Worse\WorseStepFactory;
use Phpactor\Extension\Behat\Behat\Context;
use Phpactor\Extension\Behat\Behat\Step;
use Phpactor\Extension\Behat\Behat\StepParser;
use Phpactor\WorseReflection\ReflectorBuilder;

class WorseStepFactoryTest extends TestCase
{
    public function testGeneratesSteps()
    {
        $reflector = ReflectorBuilder::create()->addSource(file_get_contents(__DIR__ . '/TestContext.php'))->build();
        $stepGenerator = new WorseStepFactory($reflector);
        $parser = new StepParser();
        $context = new Context('default', TestContext::class);
        $steps = iterator_to_array($stepGenerator->generate($parser, [ $context ]));

        $this->assertEquals([
            new Step($context, 'givenThatThis', 'that I visit Berlin'),
            new Step($context, 'shouldRun', 'I should run to Weisensee'),
        ], $steps);


    }
}
