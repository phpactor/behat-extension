Behat Extension
===============

[![Build Status](https://travis-ci.org/phpactor/behat-extension.svg?branch=master)](https://travis-ci.org/phpactor/behat-extension)

Behat integration with Phpactor.

Currently experimental, supports basic feature completion based on all
contexts found in `behat.yml` or `behat.yml.dist`.

TODO:

- [ ] Jumping to step definitions and vice-versa
- [ ] Replace entire sentences (currently suggestions are either appended to
      the existing text or, if there is an initial match, completed after the
      match).

Installation
------------

```
$ phpactor extension:install phpactor/behat-extension
```
