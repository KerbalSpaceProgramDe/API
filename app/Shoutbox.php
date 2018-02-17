<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shoutbox extends Model
{

    protected $fillable = [
        'userID',
        'username',
        'message',
        'time',
        'ipAddress',
        'fromApi'
    ];


    protected $table = 'wcf1_shoutbox_entry';
    protected $primaryKey = 'entryID';
    public $timestamps = false;
    protected $hidden = ['ipAddress', 'syncWithExternalServices'];

}
