# Spaced Repetition

Library that implements the SM-2 algorithm in PHP. See www.supermemo.com/english/ol/sm2.htm for more details.

## Install

```bash
# Install with composer
$ composer require nanokaweb/spaced-repetition
```
## Usage

```php
$sm2 = new SM2();
$sm2->processRecallResult(4);
```
