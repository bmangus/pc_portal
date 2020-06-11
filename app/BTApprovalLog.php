<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTApprovalLog extends Model
{
    protected $table = ['bt_approval_logs'];
    protected $primaryKey = ['id'];
    protected $guarded = ['id'];
}
