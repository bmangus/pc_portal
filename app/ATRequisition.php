<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATRequisition extends Model
{
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
    protected $with = ['requisitionItems'];

    public function requisitionItems()
    {
        return $this->hasMany(\App\ATRequisitionItem::class, 'RequisitionNo', 'RequisitionNo');
    }
}
