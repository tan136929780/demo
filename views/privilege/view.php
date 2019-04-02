<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $ata app\models\Privilege */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '权限'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="privilege-view">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span><strong><?= Yii::t('app', '详细信息') ?></strong> </span>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'pid', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p
                            class="form-control-static"><?= $model->getPidName($model->pid) . '&nbsp' ?> </p></div>
                </div>
                <div class="form-group">
                    <?= Html::activeLabel($model, 'name', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->name ?></p></div>
                    <?= Html::activeLabel($model, 'params', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->params ?></p></div>
                </div>
                <div class="form-group">
                    <?= Html::activeLabel($model, 'controller', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->controller ?></p></div>
                    <?= Html::activeLabel($model, 'action', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->action ?></p></div>
                </div>
                <div class="form-group">
                    <?= Html::activeLabel($model, 'deleted', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->deleted ?></p></div>
                    <?= Html::activeLabel($model, 'menu', ['class' => 'col-sm-2 control-label']) ?>
                    <div class="col-sm-4 bg-gray"><p class="form-control-static"><?= $model->menu ?></p></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-4">
                        <?= Html::a(Yii::t('app', '继续创建'), \yii\helpers\Url::to(['privilege/create']), ['class' => 'btn btn-success'])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

