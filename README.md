Behat Extension
===============

[![Build Status](https://travis-ci.org/phpactor/behat-extension.svg?branch=master)](https://travis-ci.org/phpactor/behat-extension)

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
