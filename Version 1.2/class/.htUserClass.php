<?php 

/**
 * The UserClass
 */
class UserClass
{
	// Global Variabel
	public $username;
	public $passwort;
	public $login;

	// Private Variabel
	private $allUsers;

	// Constructor
	public function __construct() 
	{
		// read the user file
		$this->allUsers=file("../.htusers.txt");
	} 

	// Public Functions
	// When the programm get the username and the passwort this function set them
	public function _setUsernameAndPasswort($username, $passwort, $login=false)
	{
		$this->username=$username;
		$this->passwort=$passwort;
		$this->login=$login;
	}

	// Login function
	public function login() {
		// Check if the login variable is false
		if (!$this->login) {
			// Then the file will be search after the Username
			foreach($this->allUsers AS $user)
			{
				$user_info = explode(";", $user);
				$username=$user_info[0];
				if (substr($user_info[1], -1)=="\n") {
					$passwort=substr($user_info[1], 0, -1);
				}
				else {
					$passwort=$user_info[1];
				}
				if (
					$username==$this->username 
					&& 
					$passwort==$this->passwort
				) 
				{
					// set the login variable true and return true
					$this->login=true;
					return true;
				}
			}
			if (!$this->login) {
				//check if after the search the file for the userername and the login variable arnt true return false
				return false;
			}
		}
		else {
			// If the login variable are from the start true return true
			return true;
		}		
	}

	// get and check the cookie
	public function getAndCheckCookie($cookie) {

		// set creadicals false
		$creadicals=false;
		foreach($this->allUsers AS $user)
		{
			$user_info = explode(";", $user);
			if (password_verify($user_info[0], $cookie)) 
			{
				// Find the user and return his username and passwort
				$creadicals=true;
				return $user_info;
			}
		}
		if (!$creadicals) {
			// if this user dont exist return false
			return false;
		}
	}

	public function addCookie($username) {
		$encodeUsername=password_hash($username, PASSWORD_DEFAULT);
		return $encodeUsername;
	}
	public function addNewUser($username, $passwort)
	{
		if (!empty($username) && !empty($passwort)) {
			unlink("../.htusers.txt");
			file_put_contents("../.htusers.txt", $username.";".$passwort."\n");
			return true;
		}
		else {
			return false;
		}
	}
}