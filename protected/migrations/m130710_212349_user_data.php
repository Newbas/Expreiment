<?php
/**
 * Crate database tables for user portfolio
 * @author helena
 *
 */
class m130710_212349_user_data extends CDbMigration
{
	public function safeUp()
	{
		//Attribute table
		$this->createTable('attribute', array(
				'attribute_id' => 'pk',
				'name' => 'VARCHAR(50) NOT NULL',
				'custom' => 'boolean DEFAULT true',
				'attribute_type_id' => 'INTEGER NOT NULL',
				'formats' => 'VARCHAR(50) NULL',
				'validation' => 'text',
				'position' => 'INTEGER NOT NULL',
				'list' => 'boolean default false',
				'attribute_group_id' => 'integer Default NULL'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		
		//attribute type table with fk for attribute
		$this->createTable('attribute_type', array(
				'attribute_type_id' => 'pk',
				'name' => 'VARCHAR(50) NOT NULL'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		$this->addForeignKey(
				'fk_attribute_attribute_type', 'attribute', 
				'attribute_type_id', 'attribute_type', 
				'attribute_type_id', 'RESTRICT', 'RESTRICT'
		);
		
		//attribute value with fk for attribute
		$this->createTable('attribute_value', array(
			'attribute_value_id' => 'pk',
			'value' => 'VARCHAR(255) NOT NULL',
			'custom' => 'boolean DEFAULT false',
			'attribute_id' => 'INTEGER NOT NULL',
			'position' => 'INTEGER NOT NULL'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		$this->addForeignKey(
				'fk_attribute_value_attribute', 'attribute_value',
				'attribute_id', 'attribute',
				'attribute_id', 'RESTRICT', 'RESTRICT'
		);
		
		//user attribute with fk for attribute
		$this->createTable('user_attribute', array(
				'user_attribute_id' => 'pk',
				'attribute_id' => 'INTEGER NOT NULL',
				'attribute_value_id' => 'INTEGER NULL',
				'value' => 'VARCHAR(255) NUll',
				'users_id' => 'INTEGER NOT NULL'
		), 'CHARACTER SET utf8 ENGINE=InnoDB');
		$this->addForeignKey(
				'fk_user_attribute_attribute', 'user_attribute',
				'attribute_id', 'attribute',
				'attribute_id', 'RESTRICT', 'RESTRICT'
		);
		$this->addForeignKey(
				'fk_user_attribute_attribute_value', 'user_attribute',
				'attribute_value_id', 'attribute_value',
				'attribute_value_id', 'RESTRICT', 'RESTRICT'
		);
		$this->addForeignKey(
				'fk_user_attribute_users', 'user_attribute',
				'users_id', 'users',
				'users_id', 'RESTRICT', 'RESTRICT'
		);
		
		//attribute group
		$this->createTable('attribute_group', array(
			'attribute_group_id' => 'pk',
			'name' => 'INTEGER NOT NULL'
		));
		$this->addForeignKey(
				'fk_attribute_attribute_group', 'attribute',
				'attribute_group_id', 'attribute_group',
				'attribute_group_id', 'RESTRICT', 'RESTRICT'
		);
		
		
		//insert predefined types
		$this->insert('attribute_type',array(
			'attribute_type_id'=>1,
			'name' => 'text',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>2,
			'name' => 'checkbox',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>3,
			'name' => 'dropdown',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>4,
			'name' => 'radio',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>5,
			'name' => 'link',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>6,
			'name' => 'media',
		));
		$this->insert('attribute_type',array(
			'attribute_type_id'=>7,
			'name' => 'medialink',
		));
		$this->insert('attribute_type',array(
				'attribute_type_id'=>7,
				'name' => 'textarea',
		));
		
		//insert groups
		$this->insert('attribute_group', array(
			'attribute_group_id' => '1',
			'name' => 'Common'
		));
		$this->insert('attribute_group', array(
			'attribute_group_id' => '2',
			'name' => 'Education'
		));
		$this->insert('attribute_group', array(
			'attribute_group_id' => '3',
			'name' => 'Common'
		));
		
		//insert predefined attributes
		$this->insert('attribute', array(
				'attribute_id' => 1,
				'name' => 'Firstname',
				'custom' => 'true',
				'attribute_type_id' => '1',
				'formats' => '',
				'position' => '0'
		));
		$this->insert('attribute', array(
				'attribute_id' => 2,
				'name' => 'Lastname',
				'custom' => 'true',
				'attribute_type_id' => '1',
				'formats' => '',
				'position' => '10'
		));
		$this->insert('attribute', array(
				'attribute_id' => 3,
				'name' => 'Sex',
				'custom' => 'false',
				'attribute_type_id' => '3',
				'formats' => '',
				'position' => '20'
		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 4,
// 				'name' => 'Speciality',
// 				'custom' => 'true',
// 				'attribute_type_id' => '1',
// 				'formats' => '',
// 				'position' => '30',
// 				'list' => '1'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 5,
// 				'name' => 'Education',
// 				'custom' => 'true',
// 				'attribute_type_id' => '1',
// 				'formats' => '',
// 				'position' => '40',
// 				'list' => '1',
// 				'attribute_group_id'=>'2'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 6,
// 				'name' => 'School',
// 				'custom' => 'true',
// 				'attribute_type_id' => '1',
// 				'formats' => '',
// 				'position' => '50',
// 				'list' => '1',
// 				'attribute_group_id'=>'2'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 7,
// 				'name' => 'Photos',
// 				'custom' => 'true',
// 				'attribute_type_id' => '6',
// 				'formats' => 'jpg,jpeg,png,gif',
// 				'position' => '60',
// 				'list' => '1'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 8,
// 				'name' => 'Videos',
// 				'custom' => 'true',
// 				'attribute_type_id' => '7',
// 				'formats' => '',
// 				'position' => '70',
// 				'list' => '1'
// 		));
		
// 		$this->insert('attribute', array(
// 				'attribute_id' => 9,
// 				'name' => 'Subscription',
// 				'custom' => 'true',
// 				'attribute_type_id' => '2',
// 				'formats' => '',
// 				'position' => '80',
// 				'list' => '1'
// 		));
		
// 		$this->insert('attribute', array(
// 				'attribute_id' => 10,
// 				'name' => 'Test checkboxes',
// 				'custom' => 'true',
// 				'attribute_type_id' => '2',
// 				'formats' => '',
// 				'position' => '90',
// 				'list' => '1'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 11,
// 				'name' => 'Test one radio',
// 				'custom' => 'true',
// 				'attribute_type_id' => '4',
// 				'formats' => '',
// 				'position' => '90',
// 				'list' => '1'
// 		));
// 		$this->insert('attribute', array(
// 				'attribute_id' => 12,
// 				'name' => 'Test many radios',
// 				'custom' => 'true',
// 				'attribute_type_id' => '4',
// 				'formats' => '',
// 				'position' => '90',
// 				'list' => '1'
// 		));
		
		//insert attribute values
		$this->insert('attribute_value', array(
				'attribute_value_id' => '1',
				'value' => 'Male',
				'custom' => '0',
				'attribute_id' => '3',
				'position' => '0'
		));
		$this->insert('attribute_value', array(
				'attribute_value_id' => '2',
				'value' => 'Female',
				'custom' => '0',
				'attribute_id' => '3',
				'position' => '1'
		));
		
// 		$this->insert('attribute_value', array(
// 				'attribute_value_id' => '3',
// 				'value' => 'Male checkbox',
// 				'custom' => '0',
// 				'attribute_id' => '10',
// 				'position' => '0'
// 		));
// 		$this->insert('attribute_value', array(
// 				'attribute_value_id' => '4',
// 				'value' => 'Female checkbox',
// 				'custom' => '0',
// 				'attribute_id' => '10',
// 				'position' => '1'
// 		));
		
// 		$this->insert('attribute_value', array(
// 				'attribute_value_id' => '5',
// 				'value' => 'Male radio',
// 				'custom' => '0',
// 				'attribute_id' => '12',
// 				'position' => '0'
// 		));
// 		$this->insert('attribute_value', array(
// 				'attribute_value_id' => '6',
// 				'value' => 'Female radio',
// 				'custom' => '0',
// 				'attribute_id' => '12',
// 				'position' => '1'
// 		));
	}

	public function safeDown()
	{
		//drop fks
		$this->dropForeignKey('fk_attribute_attribute_type', 'attribute');
		$this->dropForeignKey('fk_attribute_value_attribute', 'attribute_value');
		$this->dropForeignKey('fk_user_attribute_attribute', 'user_attribute');
		$this->dropForeignKey('fk_user_attribute_attribute_value', 'user_attribute');
		$this->dropForeignKey('fk_user_attribute_users', 'user_attribute');
		$this->dropForeignKey('fk_attribute_attribute_group', 'attribute');
		//drop tables
		$this->dropTable('attribute');
		$this->dropTable('attribute_type');
		$this->dropTable('attribute_value');
		$this->dropTable('user_attribute');
		$this->dropTable('attribute_group');
	}

}