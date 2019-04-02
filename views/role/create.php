<?php
$this->title = Yii::t('app', '用户角色');

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '创建角色')];
?>
<div class="role-create">

    <?= $this->render('_form', [
        'model' => $model,
        'privileges' => $privileges,
    ]) ?>

</div>

