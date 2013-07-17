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
	public $fieldname;
	
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
	 * TODO::CORRECT FILE VALIDATIOn
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// 		$validation = json_decode($this->attribute->validation, true);
// 		if($this->isFileAttribute()){
// 			var_dump($validation);
// 			var_dump($validation[0]);die;
// 		}
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attribute_id, users_id', 'required'),
			array('attribute_id, attribute_value_id, users_id', 'numerical', 'integerOnly'=>true),
			//array('value', 'checkValue'),
			json_decode($this->attribute->validation, true),
			array('value', 'file', 'types'=>$this->attribute->formats, 'allowEmpty'=>1),
			array('attribute_value_id', 'exist', 'className'=>'AttributeValue'),
			array('fieldname','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_attribute_id, attribute_id, attribute_value_id, users_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Before validate event handler
	 * checks if it s fiel attribute put it into file
	 */
	protected function beforeValidate(){
		return parent::beforeValidate();
	}
	
	/**
	 * Upload File if it does exist and set link into value attribute
	 * TODO::MAY BE FIX NAME f will be changes
	 */
	protected function beforeSave(){
		if($this->isFileAttribute()){
			$file = CUploadedFile::getInstanceByName('Attribute['.$this->fieldname.']');
			if($file){
				$basePath = Yii::app()->basePath.'/..';
				$path= '/upload/'.$file->name;
				$file->saveAs($basePath.$path);
				$this->attribute_value_id = null;
				$this->value = $path;
			}
			else 
				return false;
		}
		return parent::beforeSave();
	}
	
	/**
	 * Check if it s file attribute
	 * @return boolean
	 */
	protected function isFileAttribute(){
		return AttributeType::isMediaTypes($this->attribute->attributeType->name);
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