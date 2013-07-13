<?php

/**
 * This is the model class for table "user_attribute".
 *
 * The followings are the available columns in table 'user_attribute':
 * @property integer $user_attribute_id
 * @property integer $attribute_id
 * @property integer $attribute_value_id
 * @property integer $users_id
 * @property string  $value
 * 
 * The followings are the available model relations:
 * @property Users $users
 * @property Attribute $attribute
 * @property AttributeValue $attributeValue
 */
class UserAttribute extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAttribute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attribute_id, users_id', 'required'),
			array('attribute_id, attribute_value_id, users_id', 'numerical', 'integerOnly'=>true),
			array('value', 'checkValue'),
			array('attribute_value_id', 'exist', 'className'=>'AttributeValue'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_attribute_id, attribute_id, attribute_value_id, users_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Check that one of value exists
	 * @param unknown $attribute
	 * @param unknown $params
	 */
	public function checkValue($attribute, $params){
		if(!$this->value && !$this->attribute_value_id)
			$this->addError($attribute,'Value can`t be empty');
	}
	
	/**
	 *
	 * @param unknown $user
	 */
	public function userAttributes($user){
		$this->getDbCriteria()->mergeWith(array(
				'condition'=>'users_id = :user',
				'params' => array(':user'=>$user)
		));
		return $this;
	}
	
	/**
	 * scopes for model
	 */
	public function scopes(){
		return array(
			'withAttributes'=>array(
				'with' => array(
					'attributeValue'=>array(
						'joinType' => 'RIGHT JOIN',
					),
					'attribute'=>array(
						'joinType' => 'RIGHT JOIN',
					),
				),
			),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'attribute' => array(self::BELONGS_TO, 'Attribute', 'attribute_id'),
			'attributeValue' => array(self::BELONGS_TO, 'AttributeValue', 'attribute_value_id'),
		);
	}

	/**
	 * Default rules for this model
	 * return array 
	 */
	public function defaultScope(){
		return array(
// 			'with' => array(
// 				//'attribute', 'attributeValue', 'attribute'
// 			),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_attribute_id' => 'User Attribute',
			'attribute_id' => 'Attribute',
			'attribute_value_id' => 'Attribute Value',
			'users_id' => 'Users',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_attribute_id',$this->user_attribute_id);
		$criteria->compare('attribute_id',$this->attribute_id);
		$criteria->compare('attribute_value_id',$this->attribute_value_id);
		$criteria->compare('users_id',$this->users_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}