Behat Extension
===============

[![Build Status](https://travis-ci.org/phpactor/behat-extension.svg?branch=master)](https://travis-ci.org/phpactor/behat-extension)

Behat integration with Phpactor.

Features:

- [x] Step completion/suggestions in feature files.

TODO:

- [ ] Jumping to step definitions and vice-versa
- [ ] Replace entire sentences (currently suggestions are either appended to
      the existing text or, if there is an initial match, completed after the
      match).

Installation
------------

This is an experimental repository, add it to your Phpactor extension file
manually (e.g. `$HOME/.vim/plugged/phpactor/extensions.json`):

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/phpactor/behat-extension"
        }
    ],
    "minimum-stability": "dev"
```

```
$ phpactor extension:install phpactor/behat-extension
```

You will then need to let VIM know that it can use Phpactor in `feature`
(`cucumber`) files:

```
autocmd FileType cucumber setlocal omnifunc=phpactor#Complete
```
