<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function profileImage() {

        $imagePath = ($this->image) ? $this->image : 'profile/MtX9yI5T9J5iEpkAqMAMNuAeMMlFrX6DYyCq87Ro.png';

        return '/storage/' . $imagePath;
    }

    public function followers() {
        return $this->belongsToMany(User::class);
    }
}
