<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 18:21
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="update-sequence">
    <?php $form = ActiveForm::begin(['options' => [ 'class' => 'form-horizontal']]) ?>
    <?php if ($privileges): ?>
        <?php foreach ($privileges as $privilege): ?>
            <div class="form-group">
                <?= Html::label(Yii::t('app', $privilege->name), null, ['class' => 'col-sm-5 control-label'])?>
                <?= Html::hiddenInput('pid', $privilege->id)?>
                <?= Html::hiddenInput('id[]', $privilege->id)?>
                <div class="col-sm-2">
                    <?= Html::textInput('sequence[]', $privilege->sequence, ['class' => 'form-control', 'type' => 'number'])?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="form-group">
            <div class="col-sm-3 col-sm-offset-5">
                <?= Html::submitButton(Yii::t('app', '保存'), ['class' => 'btn btn-primary'])?>
            </div>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end();?>
</div>
