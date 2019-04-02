<?php
$this->title = Yii::t('app', '用户角色');

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', '修改角色');
?>
<div class="role-update">

    <?= $this->render('_form', [
        'model' => $model,
        'privileges' => $privileges
    ]) ?>

</div>