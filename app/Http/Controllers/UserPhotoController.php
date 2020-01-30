<?php

namespace App\Http\Controllers;

use Validator;
use App\UserPhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PhotoCollection;

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
    public function share(Request $request,UserPhoto $UserPhoto,$ID,Photo $photo)
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
          $SearchUserPhoto=$photo
                            ->where('userphoto_id',$AuthUser->id)
                            ->whereIn('id',$request->photos)
                            ->get();
          $RequestPhotos=array_unique($request->photos);
          $RequestPhotosCount=count($RequestPhotos);

          if ($SearchUserPhoto->count()!=$RequestPhotosCount) {
            $body =array ('Code'=>'403 Forbidden');
            return json_encode($body);

          } else {
            foreach ($RequestPhotos as $request_photo) {
              $SearchSharedPhoto=$photo
                                ->where('userphoto_id',$AuthUser->id)
                                ->where('id',$request_photo)
                                ->first();


              if ($SearchSharedPhoto->shared==null) {
                $shared_users=json_decode($SearchSharedPhoto->users);
                $shared_users[]=(int) $ID ;
                $SearchSharedPhoto->users=json_encode(array_unique($shared_users));
                $SearchSharedPhoto->save();

              }
            }

            $body= PhotoCollection::collection($photo->whereIn('id',$request->photos)->get())->pluck('id');//);//

                $arr = array( 'Code'=>'201 Created', //
                'content'=>$body); //
                return json_encode($arr); //

        /*
*/
          }
          ///////////////
        }
      }
    }
    public function users()
    {
      return UserPhoto::all();// code...
    }
}
