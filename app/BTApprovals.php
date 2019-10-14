<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTApprovals extends Model
{
    protected $table = ['b_t_approvals'];
    protected $primaryKey = ['id'];
    protected $guarded = ['id'];
}
