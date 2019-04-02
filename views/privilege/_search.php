<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrivilegeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="privilege-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-group form-inline">
        <?= Html::dropDownList('PrivilegeSearch[id]', $model->id, $privileges, ['class' => 'form-control', 'prompt' => '选择权限'])?>

        <?= Html::dropDownList('PrivilegeSearch[pid]', $model->pid, $privileges, ['class' => 'form-control', 'prompt' => '选择父级权限'])?>

        <?= Html::dropDownList('PrivilegeSearch[controller]', $model->controller, $controllers, ['class' => 'form-control', 'prompt' => '选择控制器'])?>

        <?= Html::submitButton('<i class="fa fa-search"></i> ' . Yii::t('app', '搜索'), ['class' => 'btn btn-default', 'id' => 'search']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
