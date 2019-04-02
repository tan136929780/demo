<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Privilege */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="privilege-form">
    <?php $form = ActiveForm::begin(['options' => [ 'class' => 'form-horizontal']]); ?>
    <div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?= Yii::t('app', '父级') ?></label>
            <div class="col-sm-3">
                <?= Html:: activeDropDownList($model, 'pid', $pidList, ['id' => 'pid_id', 'class' => 'form-control', 'prompt' => Yii::t('app', '选择父级权限'), 'value' => '0']) ?>
                <?= Html::error($model, 'pid') ?>
            </div>
            <label class="col-sm-2 control-label"><?= Yii::t('app', '删除') ?></label>
            <div class="col-sm-3">
                <?= Html:: activeDropDownList($model, 'deleted', ['N' => 'N', 'Y' => 'Y',], ['class' => 'form-control']) ?>
                <?= Html::error($model, 'deleted') ?>
            </div>
            <?= Html::activeHiddenInput($model, 'depth', ['id' => 'depth_id']) ?>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                <css_color class="red-star">*</css_color><?= Yii::t('app', '名称') ?></label>
            <div class="col-sm-3">
                <?= Html::activeTextInput($model, 'name', ['id' => 'name-id', 'class' => 'form-control', 'required' => 'required']) ?>
                <?= Html::error($model, 'name') ?>
            </div>
            <label class="col-sm-2 control-label"><?= Yii::t('app', '参数') ?></label>
            <div class="col-sm-3">
                <?= Html::activeTextInput($model, 'params', ['id' => 'url-id', 'class' => 'form-control']) ?>
                <?= Html::error($model, 'params') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?= Yii::t('app', '控制器') ?></label>
            <div class="col-sm-3">
                <?= Html:: activeDropDownList($model, 'controller', $allControllersList, ['id' => 'controller_id', 'class' => 'form-control', 'prompt' => Yii::t('app', '选择控制器')]) ?>
                <?= Html::error($model, 'controller') ?>
            </div>
            <label class="col-sm-2 control-label"><?= Yii::t('app', '方法') ?></label>
            <div class="col-sm-3">
                <?= Html:: activeDropDownList($model, 'action', [], ['id' => 'action_id', 'class' => 'form-control', 'prompt' => Yii::t('app', '选择方法')]) ?>
                <?= Html::error($model, 'action') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?= Yii::t('app', '是否作为菜单') ?></label>
            <div class="col-sm-3">
                <?= Html:: activeDropDownList($model, 'menu', ['N' => 'N', 'Y' => 'Y',], ['class' => 'form-control']) ?>
                <?= Html::error($model, 'menu') ?>
            </div>
            <div class="col-sm-3 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>

<?= Html::hiddenInput('controllers-url', Url::to(['privilege/get-controllers-action']), ['id' => 'controllers-url']) ?>
<?= Html::hiddenInput('depth-url', Url::to(['privilege/get-privilege-depth']), ['id' => 'depth-url']) ?>


<script type="text/javascript">
    $(function () {
        $("#pid_id").change(function () {
            var depthUrl = $("#depth-url").val();
            $.post(depthUrl, {id: $("#pid_id").val()}, function (data) {
                $("#depth_id").val(data);
            }, 'json');

        })

        $("#controller_id").change(function () {
            if ($(this).val()) {
                $("#name-id").val("/" + $(this).val());
                getControllerAction("create");
            }
        })

        $("#action_id").change(function () {
            $("#name-id").val("/" + $("#controller_id").val() + "/" + $(this).val());
        })

        if (($("button:last").html()) == "更新") {
            getControllerAction("update");
        } else {
            $("#depth_id").val("1");
        }

        function getControllerAction($operation) {
            var recordData = <?= json_encode($recordData)?>;
            var controllersUrl = $("#controllers-url").val();
            var actionId = $('#action_id');
            actionId.children().eq(0).nextAll().remove();
            $.post(controllersUrl, {cname: $("#controller_id").val()}, function (data) {
                if (data != '') {
                    $.each(data, function () {
                        if ($operation == "create") {
                            actionId.append("<option value='" + this.toLowerCase() + "'>" + this.toLowerCase() + "</option>");
                        } else {
                            if (this.toLowerCase() == recordData['action']) {
                                str = "<option value='" + this.toLowerCase() + "' selected >" + this.toLowerCase() + "</option>";
                            } else {
                                str = "<option value='" + this.toLowerCase() + "' >" + this.toLowerCase() + "</option>";
                            }
                            actionId.append(str);
                        }
                    });
                } else {
                    alert("This controller haven't any actions,please try again!");
                }
            }, 'json');
        }
    })
</script>
