<?php

use yii\db\Migration;

/**
 * Class m190220_020216_table_role_privilege
 */
class m190220_020216_table_role_privilege extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('role_privilege', [
            'id'         => $this->primaryKey(),
            'role_id'    => $this->integer(11). 'Not null default 0 comment "角色ID"',
            'privilege_id'    => $this->integer(11). 'Not null default 0 comment "权限ID"',
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('role_privilege');
    }
}
