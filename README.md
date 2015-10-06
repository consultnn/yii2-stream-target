Example config:
===============

```php
    [
        'targets' => [
            [
                'class'   => 'codemix\streamlog\Target',
                'levels'  => ['error', 'warning'],
                'url' => 'php://stderr',
                'except' => ['yii\web\HttpException:404']
            ],
            [
                'class'   => 'codemix\streamlog\Target',
                'levels'  => ['profile'],
                'url' => 'php://stdout',
            ],
        ]
    ]
```