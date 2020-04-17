<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $guarded = [];


    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function list()
    {
        return $this->belongsTo(User::class);
    }
}
