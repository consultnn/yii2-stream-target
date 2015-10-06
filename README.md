Yii2 StreamTarget
=================
StreamTarget sends log messages to the [php stream wrappers](http://php.net/manual/ru/wrappers.php).
Converts an arrays and objects to strings using Json::encode().

Example config:
===============

```php
    [
        'targets' => [
            [
                'class'   => 'consultnn\streamTarget\StreamTarget',
                'levels'  => ['error', 'warning'],
                'stream' => 'php://stderr',
                'except' => ['yii\web\HttpException:404']
            ],
            [
                'class'   => 'consultnn\streamTarget\StreamTarget',
                'levels'  => ['info'],
                'stream' => 'php://stdout',
            ]
        ]
    ]
```