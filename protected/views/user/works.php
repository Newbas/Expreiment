<div class="row">
	<?php echo CHtml::beginForm('','post',array('enctype' => 'multipart/form-data'));?>
	<div class="span4">
	    <h1>Your profile!</h1>
		    <?php foreach($attributes as $attribute):?>
		    	<div class="span5">
				<?php echo Yii::app()->portfolio->generateAttributeField($attribute);?>
				</div>
			<?php endforeach;?>
			<div class="span5">
				<input type="submit" class="btn btn-primary" value="Save" />
			</div>
	</div>
	<?php echo CHtml::endForm();?>
</div>
