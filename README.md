# Retry

A simple PHP function used to retry failed operations. Based on the work done by [igorw](https://github.com/igorw/retry/).
On a complete failure, the original exception will be thrown back rather than igorw's FailingTooHardException.

```php
<?php
// retry an operation up to 3 times
$oUsr = Bluefrg/retry(3, function () use ($iId) {
    return User::find($iId);
});
```

## Install

```bash
$ composer require bluefrg/retry:dev-master
```