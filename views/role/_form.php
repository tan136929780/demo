<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCssFile('/css/jquery.treemenu.css');

/**
 * Name: renderPrivilege
 * Desc: 迭代输出权限
 * User: tanxinachen
 * Date: 2019-02-20
 * @param array $items
 * @return string
 */
function renderPrivilege($items = [])
{
    if ($items) {
        $lis = [];
        foreach ($items as $item) {
            $hasChildren = isset($item['items']);
            $checkbox = Html::checkbox('Role[privileges][]', $item['active'], ['class' => 'box' . $item['id'], 'value' => $item['id'], 'pid' => $item['pid']]);
            $ele = $hasChildren ? '<i class="fa fa-minus"></i> ' : '';
            $ele = Html::tag('span', $ele . Yii::t('app', $item['name']));
            $ele = $checkbox . ' ' . $ele;
            if ($hasChildren) {
                $ele .= renderPrivilege($item['items']);
            }
            array_push($lis, $ele);
        }
        return Html::ul($lis, ['encode' => false]);
    }
}
?>

<div class="role-form">

    <?php
    $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'class' => '',
            'template' => "{label}\n<div class=\"col-sm-4\">{input}{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]);
    ?>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#role-tab" aria-controls="role-tab" role="tab"
                                   data-toggle="tab"><?= Yii::t('app', '用户角色') ?></a></li>
        <li role="presentation"><a href="#privilege-tab" aria-controls="privilege-tab" role="tab"
                                   data-toggle="tab"><?= Yii::t('app', '授权') ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="role-tab">
            <div class="well">
                <div class="form-group<?php if ($model->hasErrors('name') || $model->hasErrors('status')): ?> has-error<?php endif; ?>">
                    <label class="col-sm-2 control-label" for="role-name"><span
                                class="red-star">*</span><?= Yii::t('app', '角色名称') ?></label>
                    <div class="col-sm-4">
                        <?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'placeholder' => Yii::t('app', '角色名称'),'readonly' => !$model->isNewRecord]) ?>
                        <?= Html::error($model, 'name', ['tag' => 'p', 'class' => 'help-block help-block-error']) ?>
                    </div>

                    <label class="col-sm-2 control-label" for="role-status"><?= Yii::t('app', '状态') ?></label>
                    <div class="col-sm-4 form-control-static">
                        <?= Html::activeRadioList($model, 'status', $model->status()) ?>
                    </div>
                </div>

                <div class="form-group<?php if ($model->hasErrors('description')): ?> has-error<?php endif; ?>">
                    <label class="col-sm-2 control-label" for="role-description"><span
                                class="red-star">*</span><?= Yii::t('app', '角色描述') ?></label>
                    <div class="col-sm-8">
                        <?= Html::activeTextInput($model, 'description', ['class' => 'form-control', 'placeholder' => Yii::t('app', '角色描述')]) ?>
                        <?= Html::error($model, 'description', ['tag' => 'p', 'class' => 'help-block help-block-error']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="privilege-tab">
            <div class="tree well myPrivileges">
                <?= renderPrivilege($privileges) ?>
            </div>
        </div>
    </div>

    <div class="form-group submit-content">
        <div class="col-sm-offset-1">
            <?= Html::submitButton(Yii::t('app', '保存'), ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    </div>

    <?php
    ActiveForm::end();
    ?>
</div>

<script type="text/javascript">
    $(function () {
        //初始化需要active的Tab值
        if (!localStorage.getItem('businessRoleTabValue')) {
            localStorage.setItem('businessRoleTabValue', '#role-tab');
        }
        //初始显示需要active的TabContent
        $('.nav-tabs a[href="' + localStorage.businessRoleTabValue + '"]').parent().addClass('active');
        $(localStorage.businessRoleTabValue).addClass('active');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            localStorage.setItem('businessRoleTabValue', $(this).attr('href'));
        });

        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus').removeClass('fa-minus');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus').removeClass('fa-plus');
            }
            e.stopPropagation();
        });


        if (<?= 'view' == Yii::$app->controller->action->id ? 1 : 0 ?>) {
            $('.role-form input, .role-form select, .role-form textarea, .role-form button').prop('disabled', true);
            $('.submit-content').addClass('hidden');
        }
        // 权限选择点击处理
        $('.myPrivileges input:checkbox').click(function () {
            $(this).attr('pid') != '0' && checkParent($(this).attr('pid'), true); //只要孩子被選中過，父級都被選中
            checkChildren($(this).val(), $(this).prop('checked'));
        });

        //處理父級選中狀態
        function checkParent(pid, anyChecked) {
            $('.' + 'box' + pid).prop('checked', anyChecked);
            if ($('.' + 'box' + pid).attr('pid') != '0') {
                checkParent($('.' + 'box' + pid).attr('pid'), anyChecked);
            }
        }

        //處理孩子選中狀態
        function checkChildren(id, isChecked) {
            var children = $('.myPrivileges input:checkbox[pid=' + id + ']');
            children.prop('checked', isChecked);
            for (var i = 0; i < children.length; i++) {
                checkChildren($(children[i]).val(), isChecked);
            }
        }
    });
</script>
