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
	
	/**
	 * Base home page
	 */
	public function actionIndex(){
		$this->render('index');
	}
	
	
	/**
	 * Action for controling your profile
	 */
	public function actionProfile(){
		$attributes = Yii::app()->portfolio->getAttributes('Profile',Yii::app()->user->id);
		
		$avatar = Yii::app()->portfolio->getAttributes('Avatar',Yii::app()->user->id);
		if(isset($_POST['Attribute'])){
			$attributes = Yii::app()->portfolio->saveAttributes('Profile',$_POST['Attribute']);
			$avatar = Yii::app()->portfolio->saveAttributes('Avatar',$_POST['Attribute']);
		}
		$this->render('profile',array('attributes'=>$attributes, 'avatar' => $avatar[0]));
	}
	
	/**
	 * Action for showing works
	 * TODO::MOVE GET SAVE ATTRIBUTE BY TYPE TO FUNCTION
	 */
	public function actionWorks(){
		$attributes = Yii::app()->portfolio->getAttributes('Projects',Yii::app()->user->id);
		if(isset($_POST['Attribute'])){
			$attributes = Yii::app()->portfolio->saveAttributes('Projects',$_POST['Attribute']);
		}
		$this->render('works',array('attributes'=>$attributes));
	}
	
}