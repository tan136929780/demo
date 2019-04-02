<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrivilegeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '权限');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="privilege-index">
    <?= $this->render('_search', [
        'model' => $searchModel,
        'privileges' => $privileges,
        'controllers' => $controllers
    ]); ?>


    <div class="btn-toolbar list-toolbar pull-right hidden-sm hidden-xs">
        <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', '创建权限'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php if ($searchModel->pid): ?>
            <?= Html::a('Update Sequence', ['update-sequence', 'pid' => $searchModel->pid], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}\n{summary}",
        'columns' => [
            [
                'attribute' => '父级',
                'value' => function ($model) {
                    return $model->getPidName($model->pid);
                }
            ],
            [
                'attribute' => '名称',
                'value' => function ($model) {
                    return Yii::t('app', $model->name);
                }
            ],
            [
                'attribute' => '控制器',
                'value' => function ($model) {
                    return $model->controller;
                }
            ],
            [
                'attribute' => '方法',
                'value' => function ($model) {
                    return $model->action;
                }
            ],
            [
                'header' => Yii::t('app', '操作'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => '查看',
                            'aria-label' => '查看',
                            'data-pjax' => '0',
                            'class' => 'btn btn-default  btn-sm',
                        ];
                        return Html::a(Yii::t('app', '查看'), $url, $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', '编辑'),
                            'aria-label' => Yii::t('app', '编辑'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-default  btn-sm',
                        ];
                        return Html::a(Yii::t('app', '编辑'), $url, $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            $url,
                            [
                                'class' => 'btn btn-danger  btn-sm',
                                'title' => Yii::t('app', '删除'),
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('app', '确定删除吗?')
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>

</div>
