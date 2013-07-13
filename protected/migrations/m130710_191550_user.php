<?php

class m130710_191550_user extends CDbMigration
{
	public function up()
	{
		$this->createTable('users', array(
			'users_id' => 'pk',
			'email' => 'VARCHAR(50) NOT NULL',
			'password' => 'VARCHAR(40) NOT NULL',
			'salt' => 'VARCHAR(40) NOT NULL',
			'active' => 'boolean DEFAULT true'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
	}

	public function down()
	{
		$this->dropTable('users');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}