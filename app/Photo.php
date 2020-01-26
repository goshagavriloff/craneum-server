<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserPhoto;

class Photo extends Model
{
  public function userphoto()
  {
    return $this->belongsTo(UserPhoto::class);
  }

}
