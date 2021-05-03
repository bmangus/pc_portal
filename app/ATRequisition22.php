<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATRequisition22 extends Model
{
    protected $table = 'a_t_requisitions_22';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
    protected $with = ['requisitionItems'];

    public function requisitionItems() {
        return $this->hasMany('App\ATRequisitionItem', 'RequisitionNo', 'RequisitionNo');
    }
}
