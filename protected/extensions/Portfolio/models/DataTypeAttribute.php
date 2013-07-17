<?php

/**
 * This is the model class for table "data_type_attribute".
 *
 * The followings are the available columns in table 'data_type_attribute':
 * @property integer $data_type_attribute_id
 * @property integer $data_type_id
 * @property integer $attribute_id
 *
 * The followings are the available model relations:
 * @property Attribute $attribute
 * @property DataType $dataType
 */
class DataTypeAttribute extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DatatypeAttribute the static model class
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
		return 'data_type_attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('data_type_id, attribute_id', 'required'),
			array('data_type_id, attribute_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('data_type_attribute_id, data_type_id, attribute_id', 'safe', 'on'=>'search'),
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
			'attribute' => array(self::BELONGS_TO, 'Attribute', 'attribute_id'),
			'dataType' => array(self::BELONGS_TO, 'DataType', 'data_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'data_type_attribute_id' => 'Data Type Attribute',
			'data_type_id' => 'Data Type',
			'attribute_id' => 'Attribute',
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

		$criteria->compare('data_type_attribute_id',$this->data_type_attribute_id);
		$criteria->compare('data_type_id',$this->data_type_id);
		$criteria->compare('attribute_id',$this->attribute_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}