<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTRequisition extends Model
{
    protected $primaryKey = 'pk';
    protected $guarded = ['id'];
    protected $with = ['approvers', 'requisitionItems'];

    public function approvers()
    {
        return $this->hasOne(\App\BTApprovers::class, 'ProjectCode', 'Project');
    }

    public function requisitionItems()
    {
        return $this->hasMany(\App\BTRequisitionItem::class, 'zd_RequisRecID', 'RecID');
    }

    public function scopeWasApprover($query, $username)
    {
        return $query
            ->where('ApprovedBy1', $username)
            ->orWhere('ApprovedBy2', $username)
            ->orWhere('ApprovedBy3', $username)
            ->orWhere('ApprovedBy4', $username)
            ->orWhere('ApprovedBy5', $username)
            ->orWhere('ApprovedByTE', $username)
            ->orWhere('FinalApprovedBy', $username);
    }
}
