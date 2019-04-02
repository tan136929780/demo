<?php
/* @var $this yii\web\View */
/* @var $model app\models\Privilege */

$this->title = Yii::t('app', '创建权限');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '权限'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="privilege-create">

    <?= $this->render('_form', [
        'model' => $model,
        'pidList' => $pidList,
        'allControllersList' => $allControllersList,
        'recordData' => array(),
    ]) ?>

</div>
