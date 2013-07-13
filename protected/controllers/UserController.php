<?php 
class UserController extends Controller{
	
	/**
	 * Access rules for user controller
	 * @return multitype:multitype:string multitype:string
	 */
	public function accessRules(){
		return array(
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
	
	public function actionIndex(){
		$this->render('index');
	}
	
	
	/**
	 * Action for controling your profile
	 */
	public function actionProfile(){
		$attributes = Yii::app()->portfolio->getAttributes(Yii::app()->user->id);
		if(isset($_POST['Attribute'])){
			$attributes = Yii::app()->portfolio->saveAttributes($_POST['Attribute']);
		}
		$this->render('profile',array('attributes'=>$attributes));
	}
	
	public function actionWorks(){
		$this->render('works',array());
	}
	
}