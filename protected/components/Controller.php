<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $leftMenu=array();
	public $rightMenu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * (non-PHPdoc)
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
				'accessControl',
		);
	}
	
	protected function beforeAction($action){
		$this->setMenu();
		return parent::beforeAction($action);
	}
	
	protected function setMenu(){
		$this->leftMenu = array(
			array('label'=>'Home', 'url'=>array('/user/index')),
			array('label'=>'Profile', 'url'=>array('/user/profile')),
			array('label'=>'Works', 'url'=>array('/user/works'))
		);
		$this->rightMenu = array(
			array('label'=>Yii::app()->user->name,//(Yii::app()->user->isGuest ? 'Profile' : Yii::app()->user->name), 
					'url'=>array('/user/index'), 
					'itemOptions'=>array(
						'class'=>''
				)),
			array('label'=>'Home', 'url'=>array('/home/index')),
			(Yii::app()->user->isGuest ? 
				array('label'=>'Login/Register', 'url'=>array('#'), 
					'itemOptions'=>array(
						'data-target'=>'#login-dialog',
						'data-toggle'=>'modal'
					)) :
				array('label'=>'Logout', 'url'=>array('/home/logout'))),
		);
	}
	
	/**
	 * redirect or not on submit depends on dialog or not
	 * @param string  $url where to redirect
	 */
	public function renderOnSubmit($url)
	{
		$url = Yii::app()->session->get('reffererReturnUrl',array($url));
		Yii::app()->session->remove('reffererReturnUrl');
		if(Yii::app()->request->isAjaxRequest)
		{
			//Close the dialog, reset the iframe and update the grid
			$ajaxReload = "window.location.reload();";
			$script = isset($_GET['gridId']) ? "$.fn.yiiGridView.update('{$_GET['gridId']}');" : $ajaxReload ;
			echo json_encode(array('ok'=>1,'script'=>CHtml::script($script)));
			Yii::app()->end();
		}
		else
			$this->redirect($url);
	}
	
	/**
	 * Render partial if ajax and
	 * renders with layout if not ajax
	 *
	 * @param unknown_type $view view to render
	 * @param unknown_type $params params to view
	 */
	public function renderOnRequest($view,$params=array())
	{
		//new dialogs
		if(Yii::app()->request->isAjaxRequest)
		{
			$this->renderPartial('//layouts/dialog',array('content'=>$this->renderPartial($view,$params,true)));
			Yii::app()->end();
		}
		else
			$this->render($view,$params);
	}
	
}