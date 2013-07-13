<label><?php echo $attribute['label'];?></label>
<div class="errorMessage text-error"><?php echo $attribute['errors']?></div>
<?php echo CHtml::dropDownList(
	$attribute['name'],
	$attribute['value'],
	$attribute['options'],
	array(
		'class' => ''
	)
);?>