<?php

namespace App\Http\Controllers;

use Validator;
use App\Photo;
use App\UserPhoto;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function show(Photo $photo)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {

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
