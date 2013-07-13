<?php if(count($attribute['options']) > 1):?>
<?php echo CHtml::radioButtonList(
	$attribute['name'],
	$attribute['value'],
	$attribute['options'],
	array(
		'labelOptions'=>array('class' => 'radio inline')
	)
);?>
<?php else:?>
<label class="radio">
<div class="errorMessage text-error"><?php echo $attribute['errors'];?></div>
<?php echo CHtml::radioButton(
	$attribute['name'],
	$attribute['value']
);?>
<?php echo $attribute['label'];?></label>
<?php endif;?>