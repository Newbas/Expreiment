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
	public function getAttributes($user = null){
		$attributes = $this->getUserAttributes($user);
		return $this->collectAttributes($attributes);
	}
	
	/**
	 * Get from database all user attributes
	 * @param string $user
	 */
	protected function getUserAttributes($user = null){
// 		return UserAttribute::model()
// 					->withAttributes()
// 					->userAttributes($user)
// 					->findAll();
		
		return $user ? 
			Attribute::model()
				->withUserAttributes($user)
				->findAll() :
			Attribute::model()->findAll();
	}
	
	private function createAttribute($attribute, $options, $userAttribute = null){
		$id = $userAttribute ? $userAttribute->user_attribute_id : 'new'.$attribute->attribute_id;
		$value = $userAttribute ? $this->isIdType($type) ? 
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
				'errors' => ''
		);
		return $temp;
	}
	
	/**
	 * Collects attributes for showing form 
	 * 
	 * @param array $attributes list of attributes
	 */
	protected function collectAttributes($attributes){
		$result = array();
		foreach($attributes as $attribute){
			//generate options
			$options = array();
			foreach($attribute->attributeValues as $value)
				$options[$value->attribute_value_id] = $value->value;
			//TODO::add VALUE
			if(count($attribute->userAttributes) != 0){
				foreach($attribute->userAttributes as $userAttribute){
					$temp = $this->createAttribute($attribute, $options, $userAttribute);
				}
			}
			else{
				$temp = $this->createAttribute($attribute, $options);
			}
			
			array_push($result, $temp);
		}
		return $result;
	}
	
	/**
	 * Saves attributes to user
	 * 
	 * @param unknown $attributes
	 */
	public function saveAttributes($attributes){
		$user = Yii::app()->user->id;
		$dbAttributes = $this->getAttributes($user);
		$transaction = Yii::app()->db->beginTransaction();
		$mainResult = true;
		//save each attribute
		foreach($dbAttributes as $key => $attr){
			$result = true;
			$value = $attributes[$attr['id']];
			//if new attribute
			if(strpos($attr['id'], 'new') !== false){
				$result = $this->addUserAttributeValue(
					$user, $attr['attribute_id'], 
					$value, $attr['type']
				);
			}
			else{//update old attribute
				$result = $this->updateUserAttributeValue(
						$attr['id'], $attr['attribute_id'],
						$value, $attr['type']
				);
			}
			$dbAttributes[$key]['value'] = $value;
			if(!$result) $dbAttributes[$key]['errors'] = $this->lastError['value'][0];
			$mainResult = $mainResult && $result;
		}
		//commit or rollback on errors
		if($result)
			$transaction->commit();
		else 
			$transaction->rollback();
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
	public function addUserAttributeValue($user, $attribute, $value, $type, $force=false){
		$attributeValue = new UserAttribute();
		$attributeValue = $this->setUserAttributes($attributeValue, $user, $attribute, $value,$type);
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
	public function updateUserAttributeValue($pk, $attribute, $value, $type, $force=false){
		$attributeValue = UserAttribute::model()->findByPk($pk);
		$attributeValue = $this->setUserAttributes($attributeValue, $attributeValue->users_id, $attribute, $value,$type);
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
	public function setUserAttributes($attributeValue, $user, $attribute, $value, $type){
		$attributeValue->users_id = $user;
		$attributeValue->attribute_id = $attribute;
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
		return array_key_exists($type, array('dropdown', 'checkbox', 'radio'));
	}
	
}