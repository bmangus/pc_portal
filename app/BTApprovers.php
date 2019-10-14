<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTApprovers extends Model
{
    protected $table = 'bt_approvers';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
