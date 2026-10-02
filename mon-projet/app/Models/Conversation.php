<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['canal', 'duree_secondes', 'nombre_messages', 'score', 'resume'];
}