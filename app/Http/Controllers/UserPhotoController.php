<?php

namespace App\Http\Controllers;

use Validator;
use App\UserPhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PhotoCollection;
use App\Http\Resources\UserPhotoCollection;

class UserPhotoController extends Controller
{


    public function signup(Request $request)
    {

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
    public function login(Request $request)
    {

        $phone=$request->phone;
        $password=$request->password;
        $AuthUser=UserPhoto::where('phone', $phone)->where('password', $password)->first();

        if ($AuthUser===null) {
          $error=json_encode(['login'=>'Incorrect login or password']);
          abort(404,$error);
        } else {
          $body =array ('Code'=>'200 OK','content'=>array('token'=>$AuthUser->token));
          return json_encode($body);
        }

    }
    public function logout(Request $request)
    {
          $body =array ('Code'=>'200 OK');
          return json_encode($body);


    }
    public function share(Request $request,UserPhoto $UserPhoto,$ID,Photo $photo)
    {
      $token = $request->bearerToken();
      $AuthUser=UserPhoto::where('token', $token)->first();
      $SearchUserPhoto=$photo
                        ->where('userphoto_id',$AuthUser->id)
                        ->whereIn('id',$request->photos)
                        ->get();
      $RequestPhotos=array_unique($request->photos);
      $RequestPhotosCount=count($RequestPhotos);

      if ($SearchUserPhoto->count()!=$RequestPhotosCount) {
        abort(403);

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


    }
    public function users(Request $request)
    {
      $DataUsers  = $request->search;
      $pieces = explode(" ", $DataUsers);
      $get_seacrh=UserPhoto::where('first_name','like','%'.$pieces[0].'%')
                      ->orwhere('surname','like','%'.$pieces[1].'%')
                      ->orwhere('phone','like','%'.$pieces[2].'%')
                      ->get();// code...
      $body=UserPhotoCollection::collection($get_seacrh);
      $arr =array ('Code'=>'200 OK','content'=>$body);
      return json_encode($arr);


    }
}
