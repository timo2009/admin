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
	public $rightsNumber;

	// Private Variabel
	private $allUsers;
	private $allUsersRights;

    private $filename;

	// Constructor
	public function __construct() 
	{
        $this->filename = "../.htdatabase/.htusers.txt";

        $this->allUsers=file($this->filename);
        $this->allUsersRights=file("../.htdatabase/.htuserrights.txt");
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
                    password_verify($this->passwort, $passwort)
				)
				{
					// set the login variable true and return true
					$this->login=true;
					$this->rightsNumber=$user_info[2];
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

	public function checkUserRights($action)
	{
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
				$this->rightsNumber=str_replace("\n", "", $user_info[2]);
			}
		}

		// set creadicals false
		$creadicals=false;
		foreach($this->allUsersRights AS $userright)
		{
			$right_info = explode(";", $userright);

			if ($this->rightsNumber==$right_info[0]) {

				$rights=str_replace("only", "", $right_info[1]);
				$rights=str_replace("\n", "", $rights);
				if (preg_match("/-/", $rights)) {
					$rights=explode("-", $rights);

				} else {
					$rights=[$rights];
				}

				foreach($rights AS $right)
				{

					if ($right==$action) {

						$creadicals=true;
						return true;
					}
					if ($right=="superuser") {

						$creadicals=true;
						return true;
					}
				} 
			}

		}
		if(!$creadicals) {
			return false;
		}
        return false;
	}

	public function addCookie($username) {
		$encodeUsername=password_hash($username, PASSWORD_DEFAULT);
		return $encodeUsername;
	}
	public function addFirstUser($username, $passwort)
	{
		if (!empty($username) && !empty($passwort)) {
			unlink($this->filename);
//            $hashedPassword = password_hash($passwort, PASSWORD_DEFAULT);
//            file_put_contents($this->filename, $username.";".$hashedPassword.";1"."\n");
			return true;
		}
		else {
			return false;
		}
	}

	public function addUser($username, $passwort, $right) 
	{
		$file=$this->filename;
        $hashedPassword = password_hash($passwort, PASSWORD_DEFAULT);

        $data=$username.";".$hashedPassword.";".$right."\n";

		$handle=fopen($file, "a");
		fwrite($handle, $data);
		fclose($handle);

		return true;
	}

    public function safeAction($action) {
        $file="../.htdatabase/.htlog.txt";

        date_default_timezone_set('Europe/Berlin');
        $current_time = date('H:i:s d.m.Y');

        $data=$current_time.";".$this->username.";".$action."\n";

        $handle=fopen($file, "a");
        fwrite($handle, $data);
        fclose($handle);
    }
    public function getAction() {
        $file="../.htdatabase/.htlog.txt";
        return file($file);
    }

	public function findAllUsers()
	{
		$usersInUsersTxt = file($this->filename);
		foreach($usersInUsersTxt AS $line){
			$user_info = explode(";", $line);
			for ($i=0; $i < 3; $i++) 
			{ 
				if ($i==0) {
					$username=$user_info[0];
				}		
				elseif ($i==1) {
					$password=$user_info[1];
				}		
				elseif ($i==2) {
					$status=$user_info[2];
				}
			}
			$usersData[]=[
				'username' => $username, 'password' => '******', 'right' => $status];
	    }
		return $usersData;

	}

    public function editUser($username, $newStatus)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user)
        {
            $info = explode(";", $user);
            if ($info[0] == $username)
            {

                $string = $username.";".$info[1].";".$newStatus;
                $newContent = str_replace($user, $string, $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }
    public function resetPassword($username, $newPasswort)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user)
        {
            $info = explode(";", $user);
            if ($info[0] == $username)
            {
                $hashedPassword = password_hash($newPasswort, PASSWORD_DEFAULT);

                $string = $username.";".$hashedPassword.";".$info[2];
                $newContent = str_replace($user, $string, $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }

    public function deleteUser($username)
    {

        $oldContent = file_get_contents($this->filename);

        foreach ($this->allUsers as $user)
        {
            $info = explode(";", $user);
            if ($info[0] == $username)
            {
                $newContent = str_replace($user, "", $oldContent);
                file_put_contents($this->filename, $newContent);
                return true;
            }
        }

        return false;

    }
}
