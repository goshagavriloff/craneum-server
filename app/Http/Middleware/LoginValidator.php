<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
class LoginValidator
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
      'phone' => 'required',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      $error=$validator->messages() ;
      abort(422,$error);

    }
      else {
        return $next($request);

      }
    }
}
