<?php if(count($attribute['options']) > 1):?>
<?php echo CHtml::checkBoxList(
	$attribute['name'],
	$attribute['value'],
	$attribute['options'],
	array(
		'labelOptions'=>array('class' => 'checkbox inline')
	)
);?>
<?php else:?>
<label class="checkbox inline">
<div class="errorMessage text-error"><?php echo $attribute['errors']?></div>
<?php echo CHtml::checkBox(
	$attribute['name'],
	$attribute['value']
);?>
<?php echo $attribute['label'];?></label>
<?php endif;?>