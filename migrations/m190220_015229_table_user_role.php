<?php

use yii\db\Migration;

/**
 * Class m190220_015229_table_user_role
 */
class m190220_015229_table_user_role extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('user_role', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11). 'Not null default 0 comment "用户ID"',
            'role_id'    => $this->integer(11). 'Not null default 0 comment "角色ID"',
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('user_role');
    }
}
