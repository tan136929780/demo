<?php

use yii\db\Migration;

/**
 * Class m190220_015751_table_role
 */
class m190220_015751_table_role extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('role', [
            'id'             => $this->primaryKey(),
            'name'           => $this->string(255) . 'Not null default "" comment "角色名"',
            'description'    => $this->string(255) . 'Not null default "" comment "角色描述"',
            'status'         => $this->integer(11) . 'Not null default 1 comment "角色状态"',
            'create_user_id' => $this->integer(11) . ' NOT NULL DEFAULT 0 comment "创建人"',
            'update_user_id' => $this->integer(11) . ' NOT NULL DEFAULT 0 comment "修改人"',
            'updated_at'     => $this->integer(11),
            'created_at'     => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('role');
    }
}
