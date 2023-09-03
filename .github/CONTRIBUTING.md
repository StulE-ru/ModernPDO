# How to Contribute

## Pull Requests

1. Fork the ModernPDO repository
2. Create a new branch for each action ("feature/***branch_name***" or "bugfix/***branch_name***")
3. Send a pull request from each of your branches to the "develop" branch

It is very important because ModernPDO follows Gitflow.

## Style Guide

Before a pull request you must run `composer csfix` to fix the code style.

## Testing

All pull requests must be accompanied by passing unit/integration tests and static analyzes.

#### We use:
- [PHPUnit](https://github.com/sebastianbergmann/phpunit) for unit/integration tests.
- [PHPStan](https://github.com/phpstan/phpstan) and [PSalm](https://github.com/vimeo/psalm) for static analyzes.
