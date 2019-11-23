# Deployment Guide

1. Run tests

```
"./vendor/bin/phpunit" tests
```

2. Create a new Git tag

```
git tag [MAJOR].[MINOR].[PATCH]
```
> Example 1.1.0

3. Push tag to GitHub

```
git push origin --tags
```

4. Check package has been updated on [Packagist](https://packagist.org/)
