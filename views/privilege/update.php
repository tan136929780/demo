<?php
/* @var $this yii\web\View */
/* @var $model app\models\Privilege */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '权限',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '权限'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="privilege-update">
    <?= $this->render('_form', [
        'model' => $model,
        'pidList'=>$pidList,
        'allControllersList' =>$allControllersList,
        'recordData' => $model->toArray(),
    ]) ?>
</div>
