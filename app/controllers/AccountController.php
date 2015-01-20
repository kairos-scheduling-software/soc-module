<?php

class AccountController extends BaseController {

	public function register()
	{
		return View::make('account.register');
	}

	public function postRegister()
	{
		// check to see if the fields are correct
		$validate = Validator::make(Input::all(), 
			array(
				'first_name' => 'required|max:50|min:1',
				'last_name' => 'required|max:50|min:1',
				'username' => 'required|max:50|alpha_dash|unique:users',
				'email' => 'required|max:50|email|unique:users',
				'password' => 'required|min:6',
				'confirm_password' => 'required|same:password',
			));

		if($validate -> fails())
		{
			//return errors and redirect to the form
			return Redirect::route('register')-> withErrors($validate) -> withInput(Input::except('password')); 

		}
		else
		{
			//create a new user model
			$user = new User;
			$user->username = Input::get('username');
			$user->email = Input::get('email');
			$user->first = Input::get('first_name');
			$user->last = Input::get('last_name');
			$tempPassword = Input::get('password');
			$user->password = Hash::make($tempPassword);
			$user->activation_code = str_random(60);
			$user->active = 0;

			//save the user to the database
			if($user->save())
			{
				//send them an activation email
				Mail::send('emails.auth.activate', array('link' => URL::route('activate', $user->activation_code), 'username' => $user->username), 
					function($message) use ($user)
					{
						$message->to($user->email, $user->username)->subject('Activate Kairos Account');
					}
				);
			}

			//place holder we will most likely want to return them to the page they are working on/the dashboard
			return Redirect::route('home');
		}
	}

	public function activate($code)
	{
		$users = User::where('activation_code', '=', $code)->where('active', '=', 0);
		
		if($users->count() > 0)
		{
			$user = $users->first();
			
			$user->active = 1;
			$user->activation_code = '';
			
			$user->save();

			return Redirect::route('home');
		}

		return Redirect::route('home');
	}

	public function getLogin()
	{
		return View::make('account.login');
	}

	public function postLogin()
	{
		$validate = Validator::make(Input::all(), array(
				'username' => 'required',
				'password' => 'required'
		));

		if($validate->fails())
		{
			return Redirect::route('getLogin')-> withErrors($validate) -> withInput(Input::except('password'));
		}
		else
		{
			//verify the password
			$login = Auth::attempt(array(
					'username'=> Input::get('username'),
					'password'=> Input::get('password'),
					'active' => 1
			));

			if($login)
			{
				return Redirect::intended('/dashboard');
			}
		}

		return Redirect::route('getLogin')->with('global', 'username and password did not match, or the account is not active')->withInput(Input::except('password'));
	}

	public function logout()
	{
		Auth::logout();
		return Redirect::route('home');
	}

	public function manage()
	{
		return View::make('account.manage')->with([
			'page_name'	=>	'SETTINGS'
		]);
	}

	public function change_pw($id)
	{
		$user = User::find($id);
		$old_pw = Input::get('old_pw');
		$new_pw = Input::get('new_pw');

		try 
		{
			if($user->change_password($old_pw, $new_pw))
				return Response::json(['success' => 'Password was changed successfully'], 200);
			else
				return Response::json(['error' => 'Could not change password'], 500);
		}
		catch(Exception $e)
		{
			return Response::json(['error' => 'Old password is incorrect'], 400);
		}
	}

}