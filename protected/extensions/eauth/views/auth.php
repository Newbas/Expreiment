<div class="services">
  <ul class="auth-services clear">
  <?php
	foreach ($services as $name => $service) {
		echo '<li class="auth-service '.$service->id.'">';
		$html = '<span class="auth-icon '.$service->id.'"><i></i></span>';
		$html .= '<span class="auth-title">'.$service->title.'</span>';
		$html = "<div class='bttn fb'><b></b>".CHtml::link($html, array('/home/login', 'service' => $name), array(
			'class' => 'auth-link '.$service->id,
		))."</div>";
		echo $html;
		echo '</li>';
	}
  ?>
  </ul>
</div>
