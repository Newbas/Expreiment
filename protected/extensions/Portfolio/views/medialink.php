<?php if(isset($attribute['value'])):?>
<iframe title="Video player" class="video-player" type="text/html" width="640" height="390" src="<?php echo $attribute['value']?>" frameborder="0" allowFullScreen></iframe>
<?php endif;?>
<label><?php echo $attribute['label'];?></label>
<div class="errorMessage text-error"><?php echo $attribute['errors'];?></div>
<?php echo CHtml::textField(
		$attribute['name'],
		$attribute['value'],
		array(
				'class' => ''
		)
);?>