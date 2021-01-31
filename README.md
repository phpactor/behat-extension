Behat Extension
===============

![CI](https://github.com/phpactor/behat-extension/workflows/CI/badge.svg)

Behat integration with Phpactor.

![recording](https://user-images.githubusercontent.com/530801/48978534-ac405480-f0a4-11e8-9647-73c2859d99b0.gif)

Features:

- [x] Step completion/suggestions in feature files.
- [x] Jump to step definition.

Installation
------------

```
$ phpactor extension:install phpactor/behat-extension
```

You will then need to let VIM know that it can use Phpactor in `feature`
(`cucumber`) files:

```
autocmd FileType cucumber setlocal omnifunc=phpactor#Complete
```

Usage
-----

This extension expects to find `behat.yml` or `behat.yml.dist` in your project
root.

To complete a step, open a feature file and invoke omni-complete (after adding
the configuration above).

Contributing
------------

This package is open source and welcomes contributions! Feel free to open a
pull request on this repository.

Support
-------

- Create an issue on the main [Phpactor](https://github.com/phpactor/phpactor) repository.
- Join the `#phpactor` channel on the Slack [Symfony Devs](https://symfony.com/slack-invite) channel.
