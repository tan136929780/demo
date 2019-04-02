<?php
/**
 * Created by PhpStorm.
 * User: tan
 * Date: 19-2-19
 * Time: 下午2:19
 */

$this->title = '首页';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-horizontal">
    <div class="form-inline">
        <p><?= Yii::t('app', '如果您是本站用户请登录!')?></p>
    </div>
</div>
