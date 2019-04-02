<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$queryParams = Yii::$app->request->queryParams;
?>

<div class="role-search">

    <?php $form = ActiveForm::begin([
        'id' => 'role-form',
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline']
    ]); ?>

    <?= $form->field($model, 'name', ['errorOptions' => []])
        ->textInput(['placeholder' => Yii::t('app', '用户角色')])
        ->label('') ?>

    <?= $form->field($model, 'status', ['errorOptions' => []])
        ->dropDownList($model->status(), ['prompt' => Yii::t('app', '状态')])
        ->label('') ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', '搜索'), ['class' => 'btn btn-primary search']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(function () {
        //Search
        $('.search').click(function () {
            $('#role-form').submit();
        });
    });
</script>
