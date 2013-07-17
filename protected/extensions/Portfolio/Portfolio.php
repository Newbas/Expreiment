<?php 
/**
 * Component for working with user portfolio
 * Uses all models with user attributes to get and set user information
 * Incapsulates all portfolio data
 * 
 * @author Anton Melnik
 */
class Portfolio extends CComponent{
	
	/** Last error from saving model */
	private $lastError = array();
	
	/**
	 * Initialize portfolio
	 * Imports all models needed for component
	 */
	public function init(){
		Yii::import('ext.Portfolio.models.*');
	}

	/**
	 * Return list of all available attributes
	 */
	public function getAttributes($type, $user = null, &$userDataType = null){
		//$attributes = $this->getUserAttributes($user);
		if(!$user)
			$user = Yii::app()->user->id;
		if(!$userDataType)
			$userDataType = $this->getDatatype($type, $user);
		$attributes = $this->getTypeAttributes($userDataType ,$user);
		return $this->collectAttributes($userDataType, $attributes);
	}
	
	/**
	 * Get user data type, if not exists create new
	 * @param unknown $type
	 * @param unknown $user
	 * @throws Exception
	 * @return UserDataType
	 */
	public function getDatatype($type, $user){
		$dataType = DataType::model()->findByAttributes(array('name' => $type));
		if($dataType == null)
			throw new Exception('Data type not exists');
		$userDataType = UserDataType::model()
			->findByAttributes(array(
					'data_type_id'=>$dataType->data_type_id,
					'users_id' => $user
			));
		if($userDataType == null)
			$userDataType = $this->createDatatype($dataType->data_type_id, $user);
		return $userDataType;
	}
	
	/**
	 * Create datatype
	 * @param unknown $type
	 * @param unknown $user
	 * @return UserDataType
	 */
	protected function createDatatype($type, $user){
		$dataType = new UserDataType;
		$dataType->users_id = $user;
		$dataType->data_type_id = $type;
		return $dataType;
	}
	
	/**
	 * Get user data type attributes
	 */
	public function getTypeAttributes($userDataType, $user, $id = null){
		$attributes = $userDataType->getUserAttributes();
		return $attributes;
	}
	
	/**
	 * Get from database all user attributes
	 * @param string $user
	 * @deprecated
	 */
	protected function getUserAttributes($user = null){
		return $user ? 
			Attribute::model()
				->withUserAttributes($user)
				->findAll() :
			Attribute::model()->findAll();
	}
	
	private function createAttribute($userDataType, $attribute, $options){
		$userAttribute = $userDataType->isNewRecord ? 
							null :
							$attribute->userAttributes[0];
		$id = $userAttribute ? $userAttribute->user_attribute_id : 'new'.$attribute->attribute_id;
// 		if($attribute->list)
// 			$id .= '[1]';
		$value = $userAttribute ? $this->isIdType($attribute->attributeType->name) ? 
									$userAttribute->attribute_value_id :
									$userAttribute->value : '';
		$temp = array(
				'label' => $attribute->name,
				'attribute_id' => $attribute->attribute_id,
				'id' => $id,
				'name' => "Attribute[{$id}]",//$attribute->name,
				'type' => $attribute->attributeType->name,
				'group' => $attribute->attributeGroup->attribute_group_id,
				'value' => $value,//$attribute->attributeValue->attribute_value_id,
				'options' => $options,
				'errors' => '',
				'format' => $attribute->formats
		);
		return $temp;
	}
	
	/**
	 * Collects attributes for showing form 
	 * 
	 * @param array $attributes list of attributes
	 */
	protected function collectAttributes($userDataType, $attributes){
		$result = array();
// 		$attributes = $this->getTypeAttributes($userDataType ,$user);
		foreach($attributes as $attribute){
			//generate options
			$options = array();
			foreach($attribute->attributeValues as $value)
				$options[$value->attribute_value_id] = $value->value;
			//TODO::add VALUE
			$temp = $this->createAttribute($userDataType, $attribute, $options);
			
			array_push($result, $temp);
		}
		return $result;
	}
	
	/**
	 * Saves attributes to user
	 * 
	 * @param unknown $attributes
	 */
	public function saveAttributes($type, $attributes){
		$user = Yii::app()->user->id;
		$dbAttributes = $this->getAttributes($type, $user, $userDataAttributes);
		$transaction = Yii::app()->db->beginTransaction();
// 		var_dump($transaction);die;
		$userDataAttributes->save();
		$mainResult = true;
		//save each attribute
		foreach($dbAttributes as $key => $attr){
			$result = true;
			if(!isset($attributes[$attr['id']]) && !AttributeType::isMediaTypes($attr['type'])){
				throw new Excpetion('No such attribute');
			}
			$value = $attributes[$attr['id']];
			//if new attribute
			if(strpos($attr['id'], 'new') !== false){
				$result = $this->addUserAttributeValue(
					$userDataAttributes->user_data_type_id,
					$attr['attribute_id'], 
					$value, $attr['type'],$attr['id']
				);
			}
			else{//update old attribute
				$result = $this->updateUserAttributeValue(
						$attr['id'], $attr['attribute_id'],
						$value, $attr['type'],$attr['id']
				);
			}
			$dbAttributes[$key]['value'] = $value;
			if(!$result) $dbAttributes[$key]['errors'] = $this->lastError['value'][0];
			$mainResult = $mainResult && $result;
		}
		//commit or rollback on errors
		if($mainResult)
			$transaction->commit();
		else {
			$transaction->rollback();
		}
			
		return $dbAttributes;
	}
	
	/**
	 * Generate form element for attribute
	 * @param unknown $attribute
	 */
	public function generateAttributeField($attribute){
		return Yii::app()->controller->renderPartial(
				'ext.Portfolio.views.'.$attribute['type'],
				array('attribute'=>$attribute),
				true
		);
	}
	
	/**
	 * Adds attribute with its value to user
	 * 
	 * @param int $user user id
	 * @param int $attribute attribute id
	 * @param mixed $value attribute value id
	 * @param boolean $force force to add value without check
	 */
	public function addUserAttributeValue($user, $attribute, $value, $type, $fieldname, $force=false){
		$attributeValue = new UserAttribute();
		$attributeValue = $this->setUserAttributes($attributeValue, $user, $attribute, $value,$type,$fieldname);
		return $this->saveModel($attributeValue, $force);
	}
	
	/**
	 * Updates exist attribute
	 * @param int $pk id of user attribute
	 * @param int $attribute id of attribute
	 * @param mixed $value value id or string 
	 * @param type $type type 
	 * @param string $force
	 */
	public function updateUserAttributeValue($pk, $attribute, $value, $type, $fieldname, $force=false){
		$attributeValue = UserAttribute::model()->findByPk($pk);
		$attributeValue = $this->setUserAttributes($attributeValue, $attributeValue->user_data_type_id, $attribute, $value,$type, $fieldname);
		return $this->saveModel($attributeValue, $force);
	}
	
	/**
	 * Set variable for 
	 * @param object $attributeValue user attribute
	 * @param int $user user id 
	 * @param int $attribute id of attribute
	 * @param mixed $value value id or string 
	 * @return boolean
	 */
	public function setUserAttributes($attributeValue, $user_data_type_id, $attribute, $value, $type, $fieldname){
		$attributeValue->users_id = Yii::app()->user->id;
		$attributeValue->user_data_type_id = $user_data_type_id;
		$attributeValue->attribute_id = $attribute;
		$attributeValue->fieldname = $fieldname;
		$this->isIdType($type) ?
			$attributeValue->attribute_value_id = $value :
			$attributeValue->value = $value;
		return $attributeValue;
	}
	
	/**
	 * Adds attribute
	 * 
	 * @param string $name name of attribute
	 * @param int $type Attribute type id
	 * @param string $format Descrption for field (number, char, fileformats) TODO::Organize this info
	 * @param number $position position for attribute 
	 * @param boolean $force force to add value without check
	 * 
	 * @deprecated
	 */
	public function addAttribute($name, $type, $format=null, $position=0,$force=false){
		$attribute = new Attribute;
		$attribute->attribute_type_id = $type;
		$attribute->format = $format;
		$attribute->position = $position;
		return $this->saveModel($attribute, $force);
	}
	
	/**
	 * Adds attribute value
	 * 
	 * @param int $attribute id of attribute
	 * @param mixed $value value to save
	 * @param boolean $custom Custom value or predefined
	 * @param number $position Position in list
	 * @param boolean $force force to add value without check
	 * 
	 * @deprecated
	 */
	public function addAttributeValue($attribute, $value, $custom=false, $position=0, $force = false){
		$attributeValue = new AttributeValue();
		$attributeValue->attribute_id = $attribute;
		$attributeValue->value = $value;
		$attributeValue->custom = $custom;
		$attributeValue->position = $custom;
		return $this->saveModel($attributeValue, $force);
	}
	
	private function saveModel($model, $force){
		$result = $model->save(!$force);
		$this->lastError = $model->getErrors();
		return $result;
	}
	
	private function isIdType($type){
		return array_key_exists($type, array('dropdown'=>1, 'checkbox'=>1, 'radio'=>1));
	}
	
}