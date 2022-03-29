<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sources';

    protected $fillable = ['name', 'user_id'];

    public function user_id() {
        $this->hasOne(User::class);
    }
}
