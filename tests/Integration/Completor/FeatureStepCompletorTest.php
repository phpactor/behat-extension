<?php

namespace Phpactor\Extension\Behat\Tests\Integration\Completor;

use PHPUnit\Framework\TestCase;
use Phpactor\Completion\Core\Completor;
use Phpactor\Completion\Core\Suggestion;
use Phpactor\Container\PhpactorContainer;
use Phpactor\Extension\Behat\BehatExtension;
use Phpactor\Extension\ClassToFile\ClassToFileExtension;
use Phpactor\Extension\Completion\CompletionExtension;
use Phpactor\Extension\ComposerAutoloader\ComposerAutoloaderExtension;
use Phpactor\Extension\Logger\LoggingExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\FilePathResolverExtension\FilePathResolverExtension;
use Phpactor\TestUtils\ExtractOffset;

class FeatureStepCompletorTest extends TestCase
{
    /**
     * @dataProvider provideComplete
     */
    public function testComplete(string $source, array $expected)
    {
        [$source, $offset] = ExtractOffset::fromSource($source);
        $suggestions = iterator_to_array($this->completor()->complete($source, $offset));

        foreach ($expected as $index => $expectation) {
            $this->assertArraySubset($expectation, $suggestions[$index]->toArray());
        }
    }

    public function provideComplete()
    {
        yield 'all' => [ 
            <<<'EOT'
Feature: Foobar

    Scenario: Hello
        Given <>
EOT
            , [
                [
                    'type' => 'snippet',
                    'name' => 'that I visit Berlin',
                    'short_description' => ExampleContext::class
                ],
                [
                    'type' => 'snippet',
                    'name' => 'I should run to Weisensee',
                    'short_description' => ExampleContext::class
                ],
            ]
        ];

        yield 'partial match' => [ 
            <<<'EOT'
Feature: Foobar

    Scenario: Hello
        Given that I visit<>
EOT
            , [
                [
                    'type' => 'snippet',
                    'name' => ' Berlin',
                    'label' => 'that I visit Berlin',
                    'short_description' => ExampleContext::class
                ],
            ]
        ];
    }

    private function completor(): Completor
    {
        $container = PhpactorContainer::fromExtensions([
            WorseReflectionExtension::class,
            FilePathResolverExtension::class,
            CompletionExtension::class,
            BehatExtension::class,
            ClassToFileExtension::class,
            ComposerAutoloaderExtension::class,
            LoggingExtension::class,
        ], [
            FilePathResolverExtension::PARAM_APPLICATION_ROOT => __DIR__ . '/../..',
            BehatExtension::PARAM_CONFIG_PATH => __DIR__ .'/behat.yml',
        ]);
        
        
        return $container->get(CompletionExtension::SERVICE_REGISTRY)->completorForType('cucumber');
    }
}
