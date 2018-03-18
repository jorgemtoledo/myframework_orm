<?php
namespace App\Models;

// use Core\BaseModel;
// class Post extends BaseModel
use Core\BaseModelEloquent;

// use Illuminate\Database\Eloquent\Model;

// class Post extends Model
class Post extends BaseModelEloquent
{
  public $table = "posts";
  public $timestamps = false;

  protected $fillable = ['title', 'content'];

  public function rules()
  {
    return [
      'title' => 'required',
      'content' => 'min:10'
    ];
  }

  
}