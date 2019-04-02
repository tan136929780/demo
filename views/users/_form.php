<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '账户名'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'user_code', ['class' => 'form-control user-code', 'disabled' => !$model->isNewRecord]) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '用户名'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'name', ['class' => 'form-control name']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '电话'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'phone', ['class' => 'form-control phone']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '邮箱'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'email', ['class' => 'form-control email']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '省/市/自治区'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'province', ['class' => 'form-control province']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '市'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'city', ['class' => 'form-control city']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '详细地址'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'address', ['class' => 'form-control address']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '邮编'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeInput('input', $model, 'post_code', ['class' => 'form-control post-code']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '用户状态'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeDropDownList($model, 'status', $model->status(), ['class' => 'form-control status']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">
                <?= Yii::t('app', '用户类别'); ?>
            </label>
            <div class="col-sm-3">
                <?= Html::activeDropDownList($model, 'category', $model->category(), ['class' => 'form-control category']) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="userrole-role_id"><?= Yii::t('app', '用户角色') ?></label>
            <input type="hidden" name="role_desc[]" value='' id ='role_desc_arr'>
            <div class="col-sm-3">
                <?= Html::dropDownList('UserRole[role_id]', isset($model->role) ? $model->role->id : null, $roles, ['id' => 'userrole-role_id', 'class' => 'form-control', 'prompt' => Yii::t('app', '选择用户角色')]) ?>
                <small><span id = 'role_desc' data-toggle="tooltip" data-placement="top" title="<?="角色描述:" . $role_desc?>"><?=  empty($role_desc) ? '' : "角色描述:" . ( strlen($role_desc) > 100 ? " ....." : $role_desc) ?></span></small>
            </div>
        </div>
    </div>
    <div class="form-group col-lg-offset-3">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
