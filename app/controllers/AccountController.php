<?php

class AccountController extends BaseController {

	public function register()
	{
		return View::make('account.register')->with('page_name', 'register');
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
			if($validate)
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
				$schedule = new Schedule();
				$schedule->name = 'Demo Schedule';
				$schedule->last_edited_by = $user->id;
				$schedule->semester = 'Fall';
				$schedule->year = 2015;
				$schedule->description = 'This schedule is here to allow you to explore the system.  Feel free to delete it at any time.';

				$schedule->save();
				$user->schedules()->attach($schedule->id);
				
				//send them an activation email
				try
				{
				Mail::send('emails.auth.activate', array('link' => URL::route('activate', $user->activation_code), 'username' => $user->username), 
					function($message) use ($user)
					{
						$message->to($user->email, $user->username)->subject('Activate Kairos Account');
					}
				);
				}
				catch(Exception $e)
				{
					return Redirect::route('register')->with('global', 'Account could not be created at this time');
				}
			}

			//place holder we will most likely want to return them to the page they are working on/the dashboard
			return Redirect::route('register')->with('global', 'An email has been sent for account activation');
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
		return View::make('account.register')->with('page_name', 'login');
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

		return Redirect::route('getLogin')->with('global-login', 'username and password did not match, or the account is not active')->withInput(Input::except('password'));
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

	public function change_pic()
	{
		$user = Auth::user();
		$img = Input::file('pic');
		$old_file = $user->avatar;

		$filename = str_random(30) . '.' . $img->getClientOriginalExtension();
		$upload_success = $img->move(public_path() . '/profile_pics', $filename);

		if ($upload_success)
			$user->avatar = $filename;
		else
			return Response::json(['error' => 'Could not upload picture'], 500);

		if ($user->save())
		{
			if($old_file)
				unlink(public_path() . '/profile_pics/' . $old_file);

			return URL::asset('profile_pics/' . $filename);
		}
		else
			return Response::json(['error' => 'Could not upload picture'], 500);
	}

	public function toggle_emails()
	{
		$user = Auth::user();
		$user->send_email = !($user->send_email);

		if($user->save())
			return Response::json(['success' => 'Email settings changed'], 200);
		else
			return Response::json(['error' => 'Could not update email settings'], 500);
	}

	public function change_email()
	{
		$user = Auth::user();
		$user->email = Input::get('email');

		if($user->save())
			return Response::json(['success' => 'Email settings changed'], 200);
		else
			return Response::json(['error' => 'Could not update email settings'], 500);
	}

	public function change_pw()
	{
		$user = Auth::user();
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

	public function forgot_pass()
	{
		return View::make('account.forgot-pass')->with('page_name', 'password recovery');
	}

	public function forgot_pass_post()
	{
		$Validator = Validator::make(Input::all(), array(
			'email' => 'required|email'
		));

		if($Validator->fails())
		{
			return Redirect::route('forgot-pass')->withErrors($Validator)->withInput();
		}
		else
		{
			$user = User::where('email', '=', Input::get('email'));

			if($user->count())
			{
				$user = $user->first();

				$code = str_random(60);
				$password = str_random(10);

				$user->activation_code = $code;
				$user->temp_password = Hash::make($password);

				if($user->save())
				{
					Mail::send('emails.auth.passRecovery', array('url' => URL::route('password-reset', $code), 'password' => $password), function($message) use ($user)
					{
						$message->to($user->email, $user->username)->subject('Kairos password recovery');
					});

					return Redirect::route('forgot-pass')->with('global', 'A new password has been sent to your email');
				}
			}
		}

		return Redirect::route('forgot-pass')->with('global', 'Error generating password');
	}

	public function password_reset($code)
	{
		$user = User::where('activation_code', '=' , $code)->where('temp_password', '!=', '');

		if($user->count())
		{
			$user = $user->first();
			$user->password = $user->temp_password;
			$user->temp_password = '';
			$user->activation_code = '';

			if($user->save())
			{
				return Redirect::route('home');
			}
		}

		return Redirect::route('forgot-pass')->with('global', 'Could not recover your account');
	}

}
