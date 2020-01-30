<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
use Illuminate\Validation\Rule;
use App\Photo;
use App\UserPhoto;
class UpdatePhotoValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $ID = (int) request()->segment(count(request()->segments()));
      $token = $request->bearerToken();
      $AuthUser=UserPhoto::where('token', $token)->first();
      try {
        $UserPhoto=Photo::where('userphoto_id',$AuthUser->id)
                  ->where('id',$ID)
                  ->first();
      } catch (\Exception $e) {
        abort(403);
      }


      //  if ($UserPhoto===null) {

    //        } else {
              $validator = Validator::make($request->all(), [
                'photo' => ['base64image','filled'],
                'name' => 'filled',
                '_method' =>['required',Rule::in(['patch'])],
              ]);
              if ($validator->fails()) {
                $error=$validator->messages() ;
                abort(422,$error);

              }
              else {
                return $next($request);

              }
  //  }
}
}
