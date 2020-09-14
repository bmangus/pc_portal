<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTRequisitionItem extends Model
{
    protected $table = 'b_t_requisition_items';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
