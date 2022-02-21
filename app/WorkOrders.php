<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrders extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['_fmid', '_fm_system', 'SubmittedBy', 'Contact',
    'Location', 'Room', 'EmailOption', 'zd_OrderNumber', 'SubmitterEmail',
        'Problem', 'SubmitDate', 'Completed', 'EmailManager', 'Resolution',
        'AssignedDate', 'SiteNo', 'HistoryListFA', 'AssignTo', 'zd_Dept',
        'Email', 'AutoSiteNumber', 'Equipment', 'FixedAsset', 'SerialNo',
        'Model', 'New', 'Product', 'RequestType', 'zd_CreatedByLogin',
        'zd_OrderNo', 'OrderNo', 'OrderType', 'HistoryListB_G', 'Feedback',
        'Status'];

    const STATUSES = [
        'submitted' => 'Submitted',
        'assigned' => 'Assigned',
        'parts pending' => 'Parts Pending',
        'completed' => 'Completed',
    ];
}
