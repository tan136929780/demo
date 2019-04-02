<?php

use yii\db\Migration;

/**
 * Class m190218_025329_table_users
 */
class m190218_025329_table_users extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id'             => $this->primaryKey(),
            'user_code'      => $this->string(100) . ' NOT NULL DEFAULT "" comment "登录名"',
            'password'       => $this->string(200) . ' NOT NULL DEFAULT "" comment "密码"',
            'name'           => $this->string(100) . ' NOT NULL DEFAULT "" comment "名字"',
            'phone'          => $this->string(200) . ' NOT NULL DEFAULT "" comment "联系电话"',
            'email'          => $this->string(255) . ' NOT NULL DEFAULT "" comment "邮箱"',
            'province'       => $this->string(50) . ' NOT NULL DEFAULT "" comment "省份"',
            'city'           => $this->string(255) . ' NOT NULL DEFAULT "" comment "城市"',
            'address'        => $this->string(255) . ' NOT NULL DEFAULT "" comment "地址"',
            'post_code'      => $this->string(20) . ' NOT NULL DEFAULT "" comment "邮编"',
            'category'       => $this->integer(1) . ' NOT NULL DEFAULT 1 comment "客户种类：1-普通客户，2-大客户"',
            'status'         => $this->integer(1) . ' NOT NULL DEFAULT 1 comment "客户状态：1-激活，0-禁止"',
            'session_token'  => $this->string(50) . ' NOT NULL DEFAULT "" comment "客户登录状态"',
            'create_user_id' => $this->integer(11) . ' NOT NULL DEFAULT 0 comment "创建人"',
            'update_user_id' => $this->integer(11) . ' NOT NULL DEFAULT 0 comment "修改人"',
            'updated_at'     => $this->integer(11),
            'created_at'     => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
