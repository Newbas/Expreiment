<?php

class m130713_122217_objects extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable();
	}

	public function safeDown()
	{
		$this->dropTable();
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