<?php

namespace App\Http\Middleware;

use Closure;
use App\UserPhoto;

class CheckBearTokenPhotoUser
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
      $token = $request->bearerToken();
      if ($token===null) {
        abort(403);
      } else {
        $AuthUser=UserPhoto::where('token', $token)->first();
        if ($AuthUser===null) {
          $error=json_encode(["token"=>"Incorrect token"]);
          abort(404,$error);

        } else {
        return $next($request);
        }

      }



    }
}
