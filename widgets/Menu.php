<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 15:03
 */

namespace app\widgets;

use app\models\Privilege;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Menu extends Widget
{
    public function run()
    {
        function menus($data, Privilege $privilege = null, $depth = 1, $open = true)
        {
            $menus = [];
            $defaultIcon = 'fa fa-recycle';
            $icons = ['fa fa-recycle', 'fa fa-cog', 'fa fa-pie-chart', 'fa fa-dropbox','fa fa-fire','fa fa-file-text', 'glyphicon glyphicon-map-marker', 'glyphicon glyphicon-calendar'];
            if (!empty($data)) {
                foreach ($data as $k => $menu) {
                    //生成每个菜单的URL
                    $href = null;
                    if ($menu['depth'] > 1 && !empty($menu['controller']) && !empty($menu['action'])) {
                        $r = $menu['controller'] . '/' . $menu['action'];
                        $params = json_decode($menu['params'], true) ?: [];
                        $href = Url::to(([$r] + $params));
                    }
                    //菜单是否展开
                    $headOpen = ($privilege && $menu['id'] == $privilege->pid) ? 'submenu-indicator-minus' : '';
                    //加入小图标
                    if (intval($depth) == 1) {
                        $i = Html::tag('i', '', ['class' => isset($icons[$k]) ? $icons[$k] : $defaultIcon]);
                    } else {
                        $i = Html::tag('i', '', ['class' => intval($depth) == 2 ? 'fa fa-caret-right' : '']);
                    }
                    //生成菜单A连接
                    $item = Html::a($i . Yii::t('app', $menu['name']), $href, ['class' => $headOpen, 'depth' => $depth]);
                    //如果有子菜单，递归调用生成
                    if (!empty($menu['items'])) {
                        $item .= menus($menu['items'], $privilege, intval($menu['depth']) + 1, !empty($headOpen));
                    }
                    array_push($menus, $item);
                }
            }
            if (intval($depth) == 1) {
                //加入Dashboard菜单
                array_unshift($menus, '<li><a href="' . Url::to(['welcome/index']) . '"><i class="fa fa-home"></i>' . Yii::t('app', 'Dashboard') . '</a></li>');
            }
            $options = [
                'class' => intval($depth) == 1 ? '' : 'submenu',
                'encode' => false,
                'depth' => $depth,
                //比较当前URL和菜单URL，如果相等，给相应的LI添加active类
                'item' => function ($item, $index) {
                    $url = Yii::$app->getRequest()->getUrl();
                    preg_match('/^<a.*?href=\"(.*?)\".*?/i', $item, $match);
                    $activeOption = [];
                    if (isset($match[1])) {
                        $realUrl  = urldecode($url);
                        $labelUrl = urldecode(html_entity_decode($match[1]));
                        if (strpos($realUrl, $labelUrl) === 0) {
                            $activeOption['class'] = 'active';
                        }
                    }
                    return Html::tag('li', $item, $activeOption);
                }
            ];
            if ($open) {
                $options['style'] = 'display: block;';
            }

            return Html::ul($menus, $options);
        }

        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $privilege = Privilege::findOne(['controller' => $controller, 'action' => $action]);
        if ($privilege && $privilege->depth > 3) {
            $privilege = Privilege::findOne($privilege->pid);
        }

        $data = Yii::$app->getUser()->getIdentity()->getMenus();

        $menus = menus($data, $privilege, 1, true);

        $this->view->registerCssFile('css/jquery-accordion-menu.css');
        $this->view->registerJsFile('js/jquery-accordion-menu.js');
        $this->view->registerJs("jQuery('#jquery-accordion-menu').jqueryAccordionMenu();");

        return $this->render('menu', ['menus' => $menus]);
    }
}