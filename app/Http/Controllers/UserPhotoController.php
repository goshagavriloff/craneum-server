<?php

namespace App\Http\Controllers;

use Validator;
use App\UserPhoto;
use Illuminate\Http\Request;

class UserPhotoController extends Controller
{


    public function signup(Request $request)
    {
      $validator = Validator::make($request->all(), [
      'first_name' => 'required',
      'surname' => 'required',
      'phone' => 'required|unique:user_photos|max:11|min:11',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      $error=$validator->messages() ;
      $body =array ('Code'=>'422 Unprocessable entity','content'=>$error);
			return json_encode($body);


    } else {
      $User = new UserPhoto();
      $User->first_name=$request->first_name;
      $User->surname=$request->surname;
      $User->phone=$request->phone;
      $User->password=$request->password;
      $User->token=str_random(32);
      $User->save();
      $body =array ('Code'=>'201 Created','content'=>array('id'=>$User->id));
      return json_encode($body);

    }

    }
    public function login(Request $request)
    {
      $validator = Validator::make($request->all(), [
      'phone' => 'required',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      $error=$validator->messages() ;
      $body =array ('Code'=>'422 Unprocessable entity','content'=>$error);
      return json_encode($body);

    }
      else {
        $phone=$request->phone;
        $password=$request->password;
        $AuthUser=UserPhoto::where('phone', $phone)->where('password', $password)->first();

        if ($AuthUser===null) {


          $body =array ('Code'=>'404 Not found','content'=>array('login'=>'Incorrect login or password'));
          return json_encode($body);
        } else {
          $body =array ('Code'=>'200 OK','content'=>array('token'=>$AuthUser->token));
          return json_encode($body);
        }
      }
    }
    public function logout(Request $request)
    {
      $token = $request->bearerToken();
      if ($token===null) {
        $body =array ('Code'=>'403 Forbidden','content'=>array('message'=>'You need authorization'));
        return json_encode($body);
      } else {
        $AuthUser=UserPhoto::where('token', $token)->first();
        if ($AuthUser===null) {


          $body =array ('Code'=>'404 Not found','content'=>array('token'=>'Incorrect token'));
          return json_encode($body);
        } else {
          $body =array ('Code'=>'200 OK');
          return json_encode($body);
        }
      }

    }
    public function share()
    {
      return UserPhoto::all();// code...
    }
    public function users()
    {
      // code...
    }
}
