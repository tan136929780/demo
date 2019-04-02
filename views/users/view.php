<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = '用户信息';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_code',
            'name',
            'phone',
            'email',
            'province',
            'city',
            'address',
            'post_code',
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return $model->category()[$model->category];
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status()[$model->status];
                },
            ],
            [
                'attribute' => '用户角色',
                'value' => function ($model) {
                    return $model->getUserRole();
                },
            ],
            [
                'attribute' => 'create_user_id',
                'value' => function ($dataProvider) {
                    return $dataProvider->getCreateUserName();
                },
            ],
            [
                'attribute' => 'create_user_id',
                'value' => function ($dataProvider) {
                    return $dataProvider->getupdateUserName();
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                },
            ],
        ],
    ]) ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary col-lg-offset-3']) ?>
    </p>
</div>
