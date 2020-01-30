<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
class CreatePhotoValidator
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
      $validator = Validator::make($request->all(), [
      'photo' => 'required|mimes:jpg,bmp,png',

    ]);
    if ($validator->fails()) {
      $error=$validator->messages() ;

      abort(422,$error);

    } else{
      return $next($request);
    }

    }
}
