<?php

namespace App\Http\Middleware;

use Closure;
use Validator;

class RegisterValidator
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
      'first_name' => 'required',
      'surname' => 'required',
      'phone' => 'required|unique:user_photos|max:11|min:11',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      $error=$validator->messages() ;
      abort(422,$error);


    } else {
      return $next($request);
    }

    }
}
