<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = '修改用户: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'role_desc' => $role_desc,
        'role_desc_arr' => $role_desc_arr,
    ]) ?>

</div>
