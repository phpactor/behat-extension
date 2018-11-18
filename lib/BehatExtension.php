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
use Phpactor\Extension\Completion\CompletionExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
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
                new StepParser()
            );
        });

        $container->register('behat.config', function (Container $container) {
            return new BehatConfig($container->getParameter(self::PARAM_CONFIG_PATH));
        });

        $container->register('behat.completion.feature_step_completor', function (Container $container) {
            return new FeatureStepCompletor($container->get('behat.step_generator'));
        }, [ CompletionExtension::TAG_COMPLETOR => [] ]);
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
