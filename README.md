# yii2-activerecord

[![Build Status](https://travis-ci.org/sibds/yii2-activerecord.svg?branch=master)](https://travis-ci.org/sibds/yii2-activerecord)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sibds/yii2-activerecord/build-status/master)

Expanding ActiveRecord framework Yii2

## Roadmap

- [ ] Documentation
- [x] Recording status (lock/unlock)
- [x] The clone function `duplicate()`
- [x] Support for "garbage"(soft delete) for removing records
- [x] Timestamp and blameable behaviors
- [x] UserDataBehavior (extend blameable behavior)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require sibds/yii2-activerecord
```

or add

```
"sibds/yii2-activerecord": "*"
```

to the `require` section of your `composer.json` file.
