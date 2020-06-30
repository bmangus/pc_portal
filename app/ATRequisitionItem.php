<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATRequisitionItem extends Model
{
    protected $table = 'a_t_requisition_items';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
