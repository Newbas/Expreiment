<?php 
/**
 * Home controller for application
 * 
 * @author Anton Melnik
 *
 */
class HomeController extends Controller{
	
	public function accessRules(){
		return array(
			array(
				'allow',
				'users'=>array('*'),
				'actions' => array('login', 'index')
			),
			array(
				'allow',
				'users' => array('@'),
			),
			array(
				'deny',
				'users'=>array('*')
			),	
		);
	}
	
	/**
	 * Filters for this controller
	 * @return multitype:
	 */
	public function filters(){
		return CMap::mergeArray(parent::filters(),array(
			
		));
	}
	
	/**
	 * Index action (main page)
	 */
	public function actionIndex(){
		$this->render('index', array());
	}
	
	/**
	 * Login action (Ajax)
	 */
	public function actionLogin(){
		$model = new LoginForm();
		
		if(isset($_POST['LoginForm'])){
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			
			if($model->validate() && $model->login()){
				$this->renderOnSubmit('/');
			}
			else{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
		}
		
		$this->render('login',array('model'=>$form));
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Action for controling your profile
	 */
	public function actionProfile(){
		$attributes = Yii::app()->portfolio->getAttributes(Yii::app()->user->id);
		if(isset($_POST['Attribute'])){
			Yii::app()->portfolio->saveAttributes($_POST['Attribute']);
		}
		$this->render('profile',array('attributes'=>$attributes));
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error = Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
}