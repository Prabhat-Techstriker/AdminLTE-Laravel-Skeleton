<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Forgotpassword;
use Notification;
use Carbon\Carbon;
use App\User;


class AuthController extends Controller
{
	/**
	 * Create user
	 *
	 * @param  [string] name
	 * @param  [string] email
	 * @param  [string] password
	 * @param  [string] password_confirmation
	 * @return [string] message
	 */
	public function signup(Request $request)
	{
		$request->validate([
			'firstname' 		=> 'required|string',
			'lastname' 			=> 'required|string',
			'phone_number' 		=> 'required|string',
			'role' 				=> 'required|integer',
			'address' 			=> 'required|string',
			'country' 			=> 'required|string',
			'email' 			=> 'required|string|email|unique:users',
			'password' 			=> 'required|string|confirmed'
		]);

		try {
			$user = new User([
				'firstname' 	=> $request->firstname,
				'lastname' 		=> $request->lastname,
				'email' 		=> $request->email,
				'password' 		=> bcrypt($request->password),
				'phone_number' 	=> $request->phone_number,
				'address' 		=> $request->address,
				'country' 		=> $request->country,
				'role' 			=> $request->role,
				'country' 		=> $request->country
			]);
			$user->save();
			return response()->json([
				'user' => $user,
				'message' => 'Successfully created user!'
			], 201);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Failed to create user!'
			], 400);
		}
	}

	/**
	 * Login user and generate access token 
	 */
	public function login(Request $request)
	{
		$request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
			'device_token' => 'required|string'
		]);

		$data = $request->all();
		//$login_type = filter_var($data['phone_or_email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

		try {
			$token = Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);

			if (!$token) {
				return response()->json(['error' => 'Credentials wrong'], 401);
			}

			$user = User::where('email',$request->email)->first();
			$user->device_token = $data['device_token'];
			$user->save();

			$tokenResult = $user->createToken('Personal Access Token');
			$token = $tokenResult->token;
			$token->expires_at = Carbon::now()->addWeeks(1);
			if ($request->remember_me)
				$token->expires_at = Carbon::now()->addWeeks(1);
			$token->save();
			return response()->json([
				'access_token' => $tokenResult->accessToken,
				'token_type' => 'Bearer',
				'expires_at' => Carbon::parse(
					$tokenResult->token->expires_at
				)->toDateTimeString(),
				'user' => $user
			]);
			
		} catch (\Exception $e) {
			print_r($e);
			return response()->json(['error' => 'something went wrong.'], 500);
		}
	}

	/**
	 * Forgot password
	 *
	 * @return [string] email
	 */
	public function forgotPassword(Request $request){
		$request->validate([
			'email' => 'required|string|email'
		]);
		$password = $this->password_generate(7);
		$get_user_data = User::where('email', trim($request->email))->first();

		if ($get_user_data) {
			unset($get_user_data['password']);
			$get_user_data['password'] = bcrypt($password);
			$user = User::where('email', trim($request->email))->update(['password' => $get_user_data['password']]);
			if($user){
				try {
				
					$details = [
						'firstname' => $get_user_data->firstname,
						'lastname' => $get_user_data->lastname,
						'password' => $password
					];

					//Notification::send($get_user_data , new Forgotpassword($details));

					return response()->json(['Success' => true , 'message' => 'mail sent.please check you email account!.'], 200);
				} catch (\Exception $e) {
					return response()->json(['error' => 'enable to send mail.please try again!.'], 400);
				}
				
			}else{
				return response()->json(['error' => 'something went wrong.please try again!.'], 500);
			}	
		}else{
			return response()->json(['error' => 'your email not registered with our database!.'], 400);
		}
	}

	/*create random password*/
	function password_generate($chars) 
	{
		$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
		return substr(str_shuffle($data), 0, $chars);
	}

	/**
	 * Logout user (Revoke the token)
	 *
	 * @return [string] message
	 */
	public function logout(Request $request)
	{
		$request->user()->token()->revoke();
		return response()->json([
			'success' => 1,
			'message' => 'Successfully logged out!.',
		]);
	}
}
