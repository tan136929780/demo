<?php
/**
 * Created by PhpStorm.
 * User: xiangkun
 * Date: 2018-09-06
 * Time: 10:27
 *
 *
 * 增强   对数据提供类 分页的处理
 */

namespace app\helpers;
use yii\data\ArrayDataProvider;
class CaseArrayDataProvider extends ArrayDataProvider
{

    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();

            if ($pagination->getPageSize() > 0) {
                //  $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
                $models = $models;
            }
        }

        return $models;
    }
}