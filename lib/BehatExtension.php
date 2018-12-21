<?php

namespace Phpactor\Extension\Behat;

use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Behat\Adapter\Worse\WorseStepFactory;
use Phpactor\Extension\Behat\Behat\BehatConfig;
use Phpactor\Extension\Behat\Behat\StepGenerator;
use Phpactor\Extension\Behat\Behat\StepParser;
use Phpactor\Extension\Behat\Completor\FeatureStepCompletor;
use Phpactor\Extension\Behat\ReferenceFinder\StepDefinitionLocator;
use Phpactor\Extension\Completion\CompletionExtension;
use Phpactor\Extension\ReferenceFinder\ReferenceFinderExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\FilePathResolverExtension\FilePathResolverExtension;
use Phpactor\MapResolver\Resolver;

class BehatExtension implements Extension
{
    const PARAM_CONFIG_PATH = 'behat.config_path';

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register('behat.step_factory', function (Container $container) {
            return new WorseStepFactory(
                $container->get(WorseReflectionExtension::SERVICE_REFLECTOR)
            );
        });

        $container->register('behat.step_generator', function (Container $container) {
            return new StepGenerator(
                $container->get('behat.config'),
                $container->get('behat.step_factory'),
                $container->get('behat.step_parser')
            );
        });

        $container->register('behat.step_parser', function (Container $container) {
            return new StepParser();
        });

        $container->register('behat.config', function (Container $container) {
            return new BehatConfig($container->get(FilePathResolverExtension::SERVICE_FILE_PATH_RESOLVER)->resolve($container->getParameter(self::PARAM_CONFIG_PATH)));
        });

        $container->register('behat.completion.feature_step_completor', function (Container $container) {
            return new FeatureStepCompletor(
                $container->get('behat.step_generator'),
                $container->get('behat.step_parser')
            );
        }, [ CompletionExtension::TAG_COMPLETOR => [ CompletionExtension::KEY_COMPLETOR_TYPES => [ 'cucumber' ]]]);

        $container->register('behat.reference_finder.step_definition_locator', function (Container $container) {
            return new StepDefinitionLocator($container->get('behat.step_generator'), $container->get('behat.step_parser'));
        }, [ ReferenceFinderExtension::TAG_DEFINITION_LOCATOR => []]);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
        $schema->setDefaults([
            self::PARAM_CONFIG_PATH => '%project_root%/behat.yml',
        ]);
    }
}
