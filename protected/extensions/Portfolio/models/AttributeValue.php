<?php

/**
 * This is the model class for table "attribute_value".
 *
 * The followings are the available columns in table 'attribute_value':
 * @property integer $attribute_value_id
 * @property string $value
 * @property integer $custom
 * @property integer $attribute_id
 * @property integer $position
 *
 * The followings are the available model relations:
 * @property Attribute $attribute
 * @property UserAttribute[] $userAttributes
 */
class AttributeValue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AttributeValue the static model class
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
		return 'attribute_value';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value, attribute_id, position', 'required'),
			array('custom, attribute_id, position', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('attribute_value_id, value, custom, attribute_id, position', 'safe', 'on'=>'search'),
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
			'userAttributes' => array(self::HAS_MANY, 'UserAttribute', 'attribute_value_id'),
		);
	}

	public function defaultScope(){
		return array(
				'order' => $this->tableAlias.'.position'
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'attribute_value_id' => 'Attribute Value',
			'value' => 'Value',
			'custom' => 'Custom',
			'attribute_id' => 'Attribute',
			'position' => 'Position',
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

		$criteria->compare('attribute_value_id',$this->attribute_value_id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('custom',$this->custom);
		$criteria->compare('attribute_id',$this->attribute_id);
		$criteria->compare('position',$this->position);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}