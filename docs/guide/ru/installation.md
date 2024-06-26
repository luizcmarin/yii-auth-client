Установка
============

## Установка расширения

Для установки расширения используйте Composer. Запустите
                                            
```
composer require yiisoft/yii-auth-client "~2.3.0"
```

или добавьте

```json
"yiisoft/yii-auth-client": "~3.0.0"
```

в секцию `require` вашего composer.json.

## Настройка приложения

После установки расширения необходимо настроить компонент приложения auth client collection: 

```php
return [
    'components' => [
        'authClientCollection' => [
            'class' => Yiisoft\Yii\AuthClient\Collection::class,
            'clients' => [
                'google' => [
                    'class' => Yiisoft\Yii\AuthClient\Clients\Google::class,
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
                'facebook' => [
                    'class' => Yiisoft\Yii\AuthClient\Clients\Facebook::class,
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'секретный_ключ_facebook_client',
                ],
                // и т.д.
            ],
        ]
        // ...
    ],
    // ...
];
```

Из коробки предоставляются следующие клиенты:

- [[\Yiisoft\Yii\AuthClient\Clients\Facebook|Facebook]].
- [[Yiisoft\Yii\AuthClient\Clients\GitHub|GitHub]].
- Google (с помощью [[Yiisoft\Yii\AuthClient\Clients\Google|OAuth]] и [[Yiisoft\Yii\AuthClient\Clients\GoogleHybrid|OAuth Hybrid]]).
- [[Yiisoft\Yii\AuthClient\Clients\LinkedIn|LinkedIn]].
- [[Yiisoft\Yii\AuthClient\Clients\Live|Microsoft Live]].
- [[Yiisoft\Yii\AuthClient\Clients\Twitter|Twitter]].
- [[Yiisoft\Yii\AuthClient\Clients\VKontakte|VKontakte]].
- [[Yiisoft\Yii\AuthClient\Clients\Yandex|Yandex]].

Конфигурация для каждого клиента несколько отличается. Для OAuth, это обязательное получение ID клиента и секретного
ключа сервиса, который Вы собираетесь использовать. Для OpenID, в большинстве случаев, это работает из коробки.

## Хранение данных авторизации

Для того, что бы считать пользователя аутентифицированным при помощи внешнего сервиса, мы должны сохранить ID,
предоставленный при первой аутентификации, а потом проверять его при последующих попытках. Ограничивать варианты 
аутентификации только внешними сервисами, не самая лучшая идея, так как такой вид аутентификации может
потерпеть неудачу, тем самым не оставив других вариантов аутентификации для пользователя. Вместо этого лучше
обеспечить как возможность аутентификации через внешние сервисы, так и старый метод аутентификации с ипользованием 
логина и пароля.

Если мы храним информацию о пользователях в базе данных, то код соответвующей миграции может выглядеть следующим образом:

```php
class m??????_??????_auth extends \Yiisoft\Db\Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('auth');
        $this->dropTable('user');
    }
}
```

В приведённом выше примере представлена стандартная таблица `user`, используемая в шаблоне проекта Advanced для хранения
информации о пользователях. Каждый пользователь может пройти аутентификацию используя несколько внешних сервисов,
поэтому каждая запись в `user` может относится к нескольким записям в `auth`. Поле `source` в таблице `auth`
это название используемого провайдера аутентификации и `source_id` это уникальный идентификатор пользователя,
который предоставляется внешним сервисом после успешной аутентификации.

Используя таблицы, созданные ранее мы можем сгенерировать модель `Auth`. Дальнейшие настройки не требуются.

