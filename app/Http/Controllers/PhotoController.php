<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Photo;
use App\UserPhoto;
use App\Http\Resources\PhotoCollection;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Photo $photo,Request $request,$ID)
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
          $SearchUserPhoto=$photo->where('userphoto_id',$AuthUser->id)->where('id',$ID)->first();

          if ($SearchUserPhoto===null) {
            $body =array ('Code'=>'403 Forbidden');
            return json_encode($body);

          } else {
            $body= PhotoCollection::collection($photo->where('userphoto_id',$AuthUser->id)->where('id',$ID)->get());//

            $arr = array( 'Code'=>200, //
            'content'=>$body); //
            return json_encode($arr); //

          }


        }
      }
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
          $validator = Validator::make($request->all(), [
          'photo' => 'required|mimes:jpg,bmp,png',

        ]);
        if ($validator->fails()) {
          $error=$validator->messages() ;
          $body =array ('Code'=>'422 Unprocessable entity','content'=>$error);
          return json_encode($body);

        }
          else {
            $Photo = new Photo();
            $owner_id=str_random(32);
            $name='Untitled';
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();


            $filename=$name.'_'.$owner_id.'.'.$ext;
            $destination='api/img';
            try {
              $file->move($destination, $filename);
            } catch (\Exception $e) {
              $body =array ('Code'=>'422 Unprocessable entity','content'=>$e);
              return json_encode($body);
            }



            $Photo->userphoto_id=$AuthUser->id;

            $Photo->users=json_encode(array('0'=>$AuthUser->id));
            $Photo->name=$name;
            $Photo->owner_id=$owner_id;
            $Photo->ext=$ext;
            $Photo->url='http://localhost/api/img/'.$filename;
            $Photo->save();

            $body =array ('Code'=>'200 OK',
                          'content'=>array(
                                          'id'=>$Photo->id,
                                          'name'=>$Photo->name,
                                          'url'=>$Photo->url,
                                        )
                          );
            return json_encode($body);
          }
        }
      }
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo,Request $request)
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
          $SearchUserPhoto=$photo->where('userphoto_id',$AuthUser->id)->first();

          if ($SearchUserPhoto===null) {
            $body =array ('Code'=>'403 Forbidden');
            return json_encode($body);

          } else {
            $body= PhotoCollection::collection($photo->where('userphoto_id',$AuthUser->id)->get());//

            $arr = array( 'Code'=>200, //
            'content'=>$body); //
            return json_encode($arr); //

          }

        }
      } //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo,$ID)
    {
      //@update from photocontroller,composer require harishpatel143/laravel-base64-validation,migrate reset
      //
      // src https://laracasts.com/discuss/channels/laravel/create-image-from-base64-string-laravel
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
          $UserPhoto=Photo::where('userphoto_id',$AuthUser->id)
                    ->where('id',$ID)
                    ->first();
          if ($UserPhoto===null) {
                $body =array ('Code'=>'403 Forbidden');
                return json_encode($body);
              } else {
                $validator = Validator::make($request->all(), [
                  'photo' => ['base64image','filled'],
                  'name' => 'filled',
                  '_method' =>['required',Rule::in(['patch'])],
                ]);
                if ($validator->fails()) {
                  $error=$validator->messages() ;
                  $body =array ('Code'=>'422 Unprocessable entity','content'=>$error);
                  return json_encode($body);

                }
                else {

                  $new_name=$request->name;
                  $new_photo=$request->photo;


                  $destination='api/img';

                  if ($new_name===null) {
                    // code...
                  } else {
                    $old_caption=$UserPhoto->name;
                    $old_ext=$UserPhoto->owner_id.'.'.$UserPhoto->ext;
                    $old_image_type=$UserPhoto->ext;
                    $old_name=$old_caption.'_'.$old_ext;



                    $UserPhoto->name=$new_name;
                    $UserPhoto->ext=$old_image_type;
                    $UserPhoto->url='http://localhost/api/img/'.$new_name.'_'.$old_ext;

                    File::move($destination.'/'.$old_name,$destination.'/'.$new_name.'_'.$old_ext);
                    $UserPhoto->save();

                  }
                  if ($new_photo===null) {
                    // code...

                  } else {
                    $old_caption=$UserPhoto->name;
                    $old_ext=$UserPhoto->owner_id.'.'.$UserPhoto->ext;
                    $old_image_type=$UserPhoto->ext;
                    $old_name=$old_caption.'_'.$old_ext;


                    $image = $request->photo;  // your base64 encoded
                    $image = str_replace('data:image/png;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = str_random(10).'.'.'png';
                    File::put($destination. '/' . $old_name, base64_decode($image));

                  }
                  $body =array ('Code'=>'200 OK',
                                'content'=>array(
                                                'id'=>$UserPhoto->id,
                                                'name'=>$UserPhoto->name,
                                                'url'=>$UserPhoto->url,
                                              )
                                );
                  return json_encode($body);

                }
              }
        }

      }
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
