<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Yii\AuthClient\Clients;

/**
 * GoogleHybrid is an enhanced version of the [[Google]], which uses Google+ hybrid sign-in flow,
 * which relies on embedded JavaScript code to generate a sign-in button and handle user authentication dialog.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         '__class' => Yiisoft\Yii\AuthClient\Collection::class,
 *         'clients' => [
 *             'google' => [
 *                 '__class' => Yiisoft\Yii\AuthClient\Clients\GoogleHybrid::class,
 *                 'clientId' => 'google_client_id',
 *                 'clientSecret' => 'google_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * Note: Google+ hybrid relies heavily on client-side JavaScript during authorization process, do not attempt to
 * obtain authorization code using [[buildAuthUrl()]] unless you absolutely sure, what you are doing.
 *
 * JavaScript button itself generated by [[Yiisoft\Yii\AuthClient\Widgets\GooglePlusButton]] widget. If you are using
 * [[Yiisoft\Yii\AuthClient\Widgets\AuthChoice]] it will appear automatically. Otherwise you need to add it into your page manually.
 * You may customize its appearance using 'widget' key at [[viewOptions]]:
 *
 * ```php
 * 'google' => [
 *     // ...
 *     'viewOptions' => [
 *         'widget' => [
 *             '__class' => Yiisoft\Yii\AuthClient\Widgets\GooglePlusButton::class,
 *             'buttonHtmlOptions' => [
 *                 'data-approvalprompt' => 'force'
 *             ],
 *         ],
 *     ],
 * ],
 * ```
 *
 * @see Google
 * @see \Yiisoft\Yii\AuthClient\Widgets\GooglePlusButton
 * @see https://developers.google.com/+/web/signin
 */
class GoogleHybrid extends Google
{
    public $validateAuthState = false;


    protected function defaultReturnUrl()
    {
        return 'postmessage';
    }

    protected function defaultViewOptions()
    {
        return [
            'widget' => [
                '__class' => \Yiisoft\Yii\AuthClient\Widgets\GooglePlusButton::class
            ],
        ];
    }
}
