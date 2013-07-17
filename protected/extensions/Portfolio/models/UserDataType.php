<?php

/**
 * This is the model class for table "user_data_type".
 *
 * The followings are the available columns in table 'user_data_type':
 * @property integer $user_data_type_id
 * @property integer $data_type_id
 * @property integer $users_id
 *
 * The followings are the available model relations:
 * @property UserAttribute[] $userAttributes
 * @property DataType $dataType
 * @property Users $users
 */
class UserDataType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserDataType the static model class
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
		return 'user_data_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('data_type_id, users_id', 'required'),
			array('data_type_id, users_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_data_type_id, data_type_id, users_id', 'safe', 'on'=>'search'),
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
			'userAttributes' => array(self::HAS_MANY, 'UserAttribute', 'user_data_type_id'),
			'dataType' => array(self::BELONGS_TO, 'DataType', 'data_type_id'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_data_type_id' => 'User Data Type',
			'data_type_id' => 'Data Type',
			'users_id' => 'Users',
		);
	}

	public function getUserAttributes(){
		if($this->isNewRecord){
			$attributes = Attribute::model()
							->withDataTypeAttributes($this->data_type_id, $this->user_data_type_id)
							->findAll();
// 			var_dump($attributes);die;
// 			$attributes = $this->dataType->attributes;
		}
		else{
			$attributes = Attribute::model()
			->withDataTypeAttributes($this->data_type_id, $this->user_data_type_id)
// 							->withUserTypeAttributes($this->user_data_type_id, $this->data_type_id)
							->findAll();
// 			var_dump($attributes);die;
// 			$attributes = $this->userAttributes->attributes;
		}
		return $attributes;
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

		$criteria->compare('user_data_type_id',$this->user_data_type_id);
		$criteria->compare('data_type_id',$this->data_type_id);
		$criteria->compare('users_id',$this->users_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}