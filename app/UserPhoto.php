<?php

namespace App;

use App\Photo;
use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
  protected $fillable = ['first_name','surname','password','phone','token'];
  public function photo()
  {
    return $this->hasMany(Photo::class );
  }
  //
}
