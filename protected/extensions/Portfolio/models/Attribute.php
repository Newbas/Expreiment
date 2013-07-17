<?php

/**
 * This is the model class for table "attribute".
 *
 * The followings are the available columns in table 'attribute':
 * @property integer $attribute_id
 * @property string $name
 * @property integer $custom
 * @property integer $attribute_type_id
 * @property string $formats
 * @property integer $position
 * @property integer $list
 * @property integer $attribute_group_id
 * @property string $validation
 * 
 * The followings are the available model relations:
 * @property AttributeGroup $attributeGroup
 * @property AttributeType $attributeType
 * @property AttributeValue[] $attributeValues
 * @property UserAttribute[] $userAttributes
 */
class Attribute extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Attribute the static model class
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
		return 'attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, attribute_type_id, position', 'required'),
			array('custom, attribute_type_id, position, list, attribute_group_id', 'numerical', 'integerOnly'=>true),
			array('name, formats', 'length', 'max'=>50),
			array('validation', 'default','value' => '["value","checkValue"]'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('attribute_id, name, custom, attribute_type_id, formats, position, list, attribute_group_id', 'safe', 'on'=>'search'),
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
			'attributeGroup' => array(self::BELONGS_TO, 'AttributeGroup', 'attribute_group_id'),
			'attributeType' => array(self::BELONGS_TO, 'AttributeType', 'attribute_type_id'),
			'attributeValues' => array(self::HAS_MANY, 'AttributeValue', 'attribute_id'),
			'userAttributes' => array(self::HAS_MANY, 'UserAttribute', 'attribute_id'),
			'dataTypeAttributes' => array(self::HAS_MANY, 'DataTypeAttribute', 'attribute_id'),
			'dataTypes' => array(self::HAS_MANY, 'DataType', 'data_type_id',
				'through' => 'dataTypeAttributes'
			),
		);
	}

	/**
	 * scopes for model
	 */
	public function scopes(){
		return array();
	}
	
	/**
	 * Default scope for model
	 * @return multitype:string
	 */
	public function defaultScope(){
		return array(
			'order' => $this->tableAlias.'.position',
			'with' => array('attributeValues', 'attributeType', 'attributeGroup'),
		);
	}
	
	/**
	 * 
	 * @param unknown $user
	 */
// 	public function withUserAttributes($user){
// 		die;
// 		$this->getDbCriteria()->mergeWith(array(
// 	        'with'=>array('userAttributes'=>array(
// 	        	'alias' => 'userAttributes',
// 	        	'on' => 'users_id = :user',
// 	        	'joinType' => 'LEFT JOIN',
// 	        	'params' => array(':user'=>$user)
// 	        )),
// 	    ));
// 	    return $this;
// 	}
	
	/**
	 *
	 * @param unknown $user
	 */
	public function withUserTypeAttributes($type, $dataType){
		$this->getDbCriteria()->mergeWith(array(
				'with'=>array('userAttributes'=>array(
						'alias' => 'userAttributes',
						'joinType' => 'LEFT JOIN',
					),
					'dataTypeAttributes'=>array(
							'alias' => 'dataTypeAttributes',
							'on' => 'dataTypeAttributes.data_type_id = :type',
							'joinType' => 'JOIN',
							'params' => array(':type'=>$type)
					),
				),
				//'condition' => 'userAttributes.user_data_type_id = :type',
				'params' => array(':type'=>$type)
		));
		return $this;
	}
	
	/**
	 *
	 * @param unknown $user
	 */
	public function withDataTypeAttributes($type, $user_type){
		$this->getDbCriteria()->mergeWith(array(
				'with'=>array(
					'dataTypeAttributes'=>array(
						'alias' => 'dataTypeAttributes',
						'on' => 'dataTypeAttributes.data_type_id = :type',
						'joinType' => 'JOIN',
						'params' => array(':type'=>$type)
					),
					'userAttributes'=>array(
						'alias' => 'userAttributes',
						'on' => 'userAttributes.user_data_type_id = :user_type and userAttributes.users_id = :user',
						'joinType' => 'LEFT JOIN',
						'together' => true,
						'params' => array(':user_type'=>$user_type, ':user'=>Yii::app()->user->id)
					),
				),
		));
		return $this;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'attribute_id' => 'Attribute',
			'name' => 'Name',
			'custom' => 'Custom',
			'attribute_type_id' => 'Attribute Type',
			'formats' => 'Formats',
			'position' => 'Position',
			'list' => 'List',
			'attribute_group_id' => 'Attribute Group',
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

		$criteria->compare('attribute_id',$this->attribute_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('custom',$this->custom);
		$criteria->compare('attribute_type_id',$this->attribute_type_id);
		$criteria->compare('formats',$this->formats,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('list',$this->list);
		$criteria->compare('attribute_group_id',$this->attribute_group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}