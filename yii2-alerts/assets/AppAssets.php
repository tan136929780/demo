<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/24/14
 * Time: 11:47 AM
 */
namespace c006\alerts\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class AppAssets
 *
 * @package c006\alerts\assets
 */
class AppAssets extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/c006/yii2-alerts/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/c006-alerts.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @var array
     */
    public $jsOptions = [
        'position' => View::POS_END,
    ];

}
