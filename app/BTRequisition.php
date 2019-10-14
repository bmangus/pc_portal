<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTRequisition extends Model
{

    protected $primaryKey = 'pk';
    protected $guarded = ['id'];

    public function approvers() {
        return $this->hasOne('App\BTApprovers', 'ProjectCode', 'Project');
    }
}
