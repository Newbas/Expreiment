<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="/css/main.css" rel="stylesheet">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42382114-1', 'myexperiments.eu');
  ga('send', 'pageview');

</script>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile('/js/bootstrap.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile('/js/helpers.js');?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

 <div class="container-narrow">

      <div class="masthead">
       <?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$this->rightMenu,
			'htmlOptions'=>array(
				'class'=>'nav nav-pills pull-right'
			),
		)); ?>
		
		<?php if(Yii::app()->user->isGuest):?>
		<div id="login-dialog" class="modal hide fade" role="dialog">
	  	<?php echo CHtml::beginForm(array('/home/login'),'post',array('class'=>'form-signin'));?>
	        <h2 class="form-signin-heading">Please sign in</h2>
	        <label>Username</label>
	        <div class="errorMessage text-error"></div>
	        <?php echo CHtml::textField('LoginForm[username]','',
	        		array(
						'placeholder'=>'Username',
						'class'=>"input-xlarge"
					)
	        );?>
	        <label>Password</label>
	        <div class="errorMessage text-error"></div>
	        <?php echo CHtml::passwordField('LoginForm[password]','',
	        		array(
						'placeholder'=>'Password',
						'class'=>"input-xlarge"
					)
	        );?>
	        <label class="checkbox">
	        	<?php echo CHtml::checkBox('LoginForm[rememberMe]','remember-me',array());?>
	        	Remember me
	        </label>
	        <button class="btn btn-large btn-primary ajax-submit" type="submit">Sign in</button>
	      	<button class="btn btn-large" data-dismiss="modal" aria-hidden="true">Close</button>
	      </form>
	      <div class="row">
	      	<?php $this->widget('ext.eauth.EAuthWidget');?>
	      	</div>
	  </div>
	  <?php endif;?>
		<h3 class="muted text-left"><?php echo Yii::app()->name;?></h3>
      </div>
	  
      <hr>
	
	<div class="row">
      <?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$this->leftMenu,
			'htmlOptions'=>array(
				'class'=>'nav nav-pills pull-left'
			),
		)); ?>
		<?php if(!Yii::app()->user->isGuest):?>
		<div class="btn-group">
		<a class="btn dropdown-toggle btn-success btn-small " style="margin-top: 4px;" data-toggle="dropdown" href="#">
			Add
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li><?php echo CHtml::link('Project', array('/user/works'))?></li>
		<!-- dropdown menu links -->
		</ul>
		</div>
		<?php endif;?>
      </div>
      <?php echo $content;?>
      
      <hr>

      <div class="row-fluid marketing">
        <div class="span6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>

        <div class="span6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>
      </div>

      <hr>

      <div class="footer">
        <p>&copy; Company 2013</p>
      </div>



</body>
</html>