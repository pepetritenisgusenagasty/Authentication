<?php

namespace App\Auth;

use App\Models\User;


class Auth
{
	public function user()
	{
		return User::find($_SESSION['user']);
	}
   public function check()
   {
   	return isset($_SESSION['user']);
   }	
   public function attempt($email, $password)
   {
   	 // Grab User by email
        $user = User::where('email', $email)->first();

   	// If !user return false
         if (!$user) {
         	return false;
         }

   	// Verify password for that user
         if (password_verify($password, $user->password)) {
         	$_SESSION['user'] = $user->id;
         	return true;
         }

         return false;
   	// Set into session
   }


   public function logout(){
      unset($_SESSION['user']);
   }
}
