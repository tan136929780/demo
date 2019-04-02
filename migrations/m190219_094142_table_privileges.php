<?php

use yii\db\Migration;

/**
 * Class m190219_094142_table_privillege
 */
class m190219_094142_table_privileges extends Migration
{
    public function up()
    {
        $this->createTable('privileges', [
            'id'         => $this->primaryKey(),
            'pid'        => $this->integer(11) . ' Not null default 0 comment "菜单父级ID"',
            'name'       => $this->string(200) . 'Not null default "" comment "菜单名称"',
            'controller' => $this->string(50) . 'Not null default "" comment "URL对应的controller"',
            'action'     => $this->string(50) . 'Not null default "" comment "URL对应的action"',
            'params'     => $this->string(255) . 'Not null default "" comment "JSON序列化参数"',
            'sequence'   => $this->smallInteger(4) . 'Not null default 0 comment "菜单排序顺序"',
            'depth'      => $this->tinyInteger(2) . 'Not null default 0 comment "菜单深度"',
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
        ]);
        $this->addColumn('privileges', 'deleted', 'enum("Y","N") NOT NULL DEFAULT "Y" COMMENT "软删除" after depth');
        $this->addColumn('privileges', 'menu', 'enum("Y","N") NOT NULL DEFAULT "Y" COMMENT "是否左侧菜单"  after depth');
    }

    public function down()
    {
        $this->dropTable('privileges');
    }
}
