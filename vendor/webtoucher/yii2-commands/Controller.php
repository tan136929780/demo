<?php
/**
 * @link https://github.com/webtoucher/yii2-commands
 * @copyright Copyright (c) 2014 webtoucher
 * @license https://github.com/webtoucher/yii2-commands/blob/master/LICENSE.md
 */

namespace webtoucher\commands;


/**
 * Console controller wrapper
 *
 * @author Alexey Kuznetsov <mirakuru@webtoucher.ru>
 * @since 2.0
 */
abstract class Controller extends \yii\console\Controller
{
    public $defaultAction = 'run';

    /**
     * Whether every confirm can be skipped with answer 'yes'.
     *
     * @var bool
     */
    public $y = false;

    abstract public function actionRun();

    /**
     * @inheritdoc
     */
    public function options($actionId)
    {
        return array_merge(
            parent::options($actionId),
            ['y']
        );
    }

    /**
     * @inheritdoc
     */
    public function confirm($message, $default = false)
    {
        if ($this->y) {
            return true;
        }
        return parent::confirm($message, $default);
    }
}
