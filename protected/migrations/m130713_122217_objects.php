<?php

class m130713_122217_objects extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('data_type',array(
			'data_type_id' => 'pk',
			'name' => 'varchar(40)',
			'multi' => 'boolean DEFAULT 0',
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		
		$this->createTable('data_type_attribute',array(
			'data_type_attribute_id' => 'pk',
			'data_type_id' => 'integer not null',
			'attribute_id' => 'integer not null'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		$this->addForeignKey(
				'fk_data_type_attributes_data_type', 'data_type_attribute',
				'data_type_id', 'data_type',
				'data_type_id', 'RESTRICT', 'RESTRICT'
		);
		$this->addForeignKey(
				'fk_data_type_attributes_attribute', 'data_type_attribute',
				'attribute_id', 'attribute',
				'attribute_id', 'RESTRICT', 'RESTRICT'
		);
		
		$this->createTable('user_data_type',array(
			'user_data_type_id' => 'pk',
			'data_type_id' => 'integer not null',
			'users_id' => 'integer not null',
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		$this->addForeignKey(
				'fk_user_data_type_users', 'user_data_type',
				'users_id', 'users',
				'users_id', 'RESTRICT', 'RESTRICT'
		);
		$this->addForeignKey(
				'fk_user_data_type_data_type', 'user_data_type',
				'data_type_id', 'data_type',
				'data_type_id', 'RESTRICT', 'RESTRICT'
		);
		
		$this->addColumn('user_attribute', 'user_data_type_id', 'INTEGER');
		$this->addForeignKey(
				'fk_user_attribute_user_data_type', 'user_attribute',
				'user_data_type_id', 'user_data_type',
				'user_data_type_id', 'RESTRICT', 'RESTRICT'
		);
		
		$this->insert('data_type',array(
			'data_type_id' => '1',
			'name' => 'Profile',
		));
		$sql = "INSERT INTO data_type_attribute (data_type_id, attribute_id) SELECT 1, attribute_id from attribute;";
		$this->execute($sql);
		$this->insert('data_type',array(
				'data_type_id' => '2',
				'name' => 'Projects',
				'multi' => 1
		));
		$this->insert('data_type',array(
				'data_type_id' => '3',
				'name' => 'Avatar',
				'multi' => 0
		));
		
		$this->insert('attribute', array(
			'attribute_id' => 4,
			'name' => 'Avatar image',
			'attribute_type_id' => '6',
			'formats' => 'jpg,png,gif',
			'position' => '40'
		));
		$this->insert('data_type_attribute',array(
				'data_type_id' => '3', 
				'attribute_id' => '4'
		));
	}

	public function safeDown()
	{
		$this->dropForeignKey('fk_data_type_attributes_data_type', 'data_type_attribute');
		$this->dropForeignKey('fk_data_type_attributes_attribute', 'data_type_attribute');
		$this->dropForeignKey('fk_user_data_type_users', 'user_data_type');
		$this->dropForeignKey('fk_user_data_type_data_type', 'user_data_type');
		$this->addForeignKey('fk_user_attribute_user_data_type', 'user_attribute');
		
		$this->dropTable('data_type');
		$this->dropTable('data_type_attribute');
		$this->dropTable('user_data_type');
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