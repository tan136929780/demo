<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = '用户';
$this->params['breadcrumbs'][] = $this->title;
$currentUser                   = \app\models\User::currentUser();
$canView                       = $currentUser->hasPrivilege('创建用户');
$canUpdate                     = $currentUser->hasPrivilege('修改用户');
$canDelete                     = $currentUser->hasPrivilege('删除用户');
?>
<div class="users-index">

    <div class="form-horizontal">
        <div class="form-inline">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>

        <div class="form-horizontal pull-right">
            <p>
                <?= Html::a(Yii::t('app', '创建用户'), ['create'], ['class' => 'btn btn-success pull-righ']) ?>
            </p>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'       => "{items}\n{pager}\n{summary}",
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'value'     => function ($dataProvider) {
                    return $dataProvider->category()[$dataProvider->category];
                },
            ],
            [
                'attribute' => 'status',
                'value'     => function ($dataProvider) {
                    return $dataProvider->status()[$dataProvider->status];
                },
            ],
            [
                'attribute' => '用户角色',
                'value'     => function ($model) {
                    return $model->getUserRole();
                },
            ],
            [
                'attribute' => 'create_user_id',
                'value'     => function ($dataProvider) {
                    return $dataProvider->getCreateUserName();
                },
            ],
            [
                'attribute' => 'create_user_id',
                'value'     => function ($dataProvider) {
                    return $dataProvider->getupdateUserName();
                },
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => ' {view}&nbsp;' . ($canUpdate ? '{update}&nbsp;' : '') . ($canDelete ? '{delete}' : ''),
                'header'   => '操作',
                'buttons'  => [
                    'view'   => function ($url, $model) {
                        return Html::a('<span>查看</span>', $url);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span>修改</span>', $url);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span>删除</span>', $url, [
                                'data' => [
                                    'confirm' => '确 定 删 除 ?',
                                    'method'  => 'post'
                                ],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
