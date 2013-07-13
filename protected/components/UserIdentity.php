<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	
	const ERROR_USER_NOT_ACTIVATED = 3;
	
	protected $_user;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = Users::model()->findByAttributes(array('email'=>$this->username));
		if($user === null){
			$user = new Users('register');
			$user->email  = $this->username;
			$user->password  = $this->password;
			$user->validate() && $user->save();
			$this->_user = $user;
			$this->errorCode=self::ERROR_NONE;
		}
		$this->_user = $user;
		
		if(!$user->active)
			$this->errorCode = self::ERROR_USER_NOT_ACTIVATED;
		else if($user->password!==sha1($this->password))//.$user->salt
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CUserIdentity::getId()
	 */
	public function getId()
	{
		return $this->_user->users_id;
	}
	
}