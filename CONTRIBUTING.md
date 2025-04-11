# Contributing

First of all, **thank you** for contributing!

Here are a few rules to follow in order to ease code reviews and merging:

- Follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PER-02](https://www.php-fig.org/per/coding-style/) and Symfony [Best Practices](https://symfony.com/doc/current/best_practices.html)
- Run the test suite (via `make ci`)
- Write (or update) unit tests when applicable
- Write documentation for new features
- Use [commit messages that make sense](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)

One may ask you to [squash your commits](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html) too. This is used to "clean" your pull request before merging it (we don't want commits such as `fix tests`, `fix 2`, `fix 3`, etc.).

When creating your pull request on GitHub, please write a description which gives the context and/or explains why you are creating it.
