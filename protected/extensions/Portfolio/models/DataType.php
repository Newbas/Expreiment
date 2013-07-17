<?php

/**
 * This is the model class for table "data_type".
 *
 * The followings are the available columns in table 'data_type':
 * @property integer $data_type_id
 * @property string $name
 * @property integer $multi
 *
 * The followings are the available model relations:
 * @property DataTypeAttribute[] $dataTypeAttributes
 * @property UserDataType[] $userDataTypes
 */
class DataType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DataType the static model class
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
		return 'data_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('multi', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('data_type_id, name, multi', 'safe', 'on'=>'search'),
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
			'dataTypeAttributes' => array(self::HAS_MANY, 'DataTypeAttribute', 'data_type_id'),
			'userDataTypes' => array(self::HAS_MANY, 'UserDataType', 'data_type_id'),
			'attributes' => array(self::HAS_MANY, 'Attribute', 'attribute_id',
				'through' => 'dataTypeAttributes'
			),
		);
	}

	public function scopes(){
		
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'data_type_id' => 'Data Type',
			'name' => 'Name',
			'multi' => 'Multi',
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

		$criteria->compare('data_type_id',$this->data_type_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('multi',$this->multi);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}