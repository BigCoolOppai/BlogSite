<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    protected $fillable = ['title', 'description', 'content', 'thumbnail', 'category_id'];
    public function tags()
    
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();

    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

   public static function uploadImage(Request $request, $currentImage = null)
{
    if ($request->hasFile('thumbnail')) {
        if ($currentImage) {
            Storage::delete($currentImage);
        }
        $folder = date('Y-m-d');
        return $request->file('thumbnail')->store("images/{$folder}");
    }
    return $currentImage;
}

public function getImage()
{
    if ($this->thumbnail) {
        return asset("uploads/{$this->thumbnail}");
    }
     return asset("no-image.png");
}

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
            ];
    }
}
