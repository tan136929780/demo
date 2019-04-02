<?php
$this->title = Yii::t('app', '角色权限');

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', '查看角色权限');
?>
<div class="role-view">

    <?= $this->render('_form', [
        'model' => $model,
        'privileges' => $privileges,
    ]) ?>
</div>
