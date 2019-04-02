<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="users-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <div class="form-group-lg">
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeInput('input', $model, 'user_code', ['class' => 'form-control user-code', 'placeholder' => Yii::t('app', '账户名')]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeInput('input', $model, 'name', ['class' => 'form-control name', 'placeholder' => Yii::t('app', '用户名')]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeInput('input', $model, 'phone', ['class' => 'form-control phone', 'placeholder' => Yii::t('app', '联系电话')]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeInput('input', $model, 'email', ['class' => 'form-control email', 'placeholder' => Yii::t('app', '邮箱')]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeDropDownList($model, 'status', $model->status(), ['class' => 'form-control status', 'prompt' => Yii::t('app', '选择用户状态')]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?= Html::activeDropDownList($model, 'category', $model->category(), ['class' => 'form-control category', 'prompt' => Yii::t('app', '选择用户类别')]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-2 form-group">
            <?= Html::submitButton(Yii::t('app', '查找'), ['class' => 'btn btn-primary', 'id' => 'submit-btn']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
