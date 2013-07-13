<label><?php echo $attribute['label'];?></label>
<div class="errorMessage text-error"><?php echo $attribute['errors']?></div>
<?php echo CHtml::textField(
		$attribute['name'],
		$attribute['value'],
		array(
			'class' => ''
		)
);?>