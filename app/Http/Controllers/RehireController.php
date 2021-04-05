<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RehireController extends Controller
{
    public $schoolMap = [
        'Apollo Elementary' => 'AES_Everyone',
        'Arbor Grove Elementary' => 'AGES_Everyone',
        'Central Elementary' => 'CES_Everyone',
        'Coronado Heights Elementary' => 'CHES_Everyone',
        'Kenneth Cooper Middle School' => 'CMS_Everyone',
        'James L. Capps Middle School' => 'CNMS_Everyone',
        'James L Dennis Elementary' => 'DENES_Everyone',
        'Ralph Downs Elementary' => 'DOES_Everyone',
        'Hilldale Elementary' => 'HES_Everyone',
        'Harvest Hills Elementary' => 'HHES_Everyone',
        'Hefner Middle School' => 'HMS_Everyone',
        'Kirkland Elementary' => 'KES_Everyone',
        'Lake Park Elementary' => 'LPES_Everyone',
        'Mayfield Middle School' => 'MMS_Everyone',
        'Northridge Elementary' => 'NES_Everyone',
        'Overholser Elementary' => 'OES_Everyone',
        'Putnam City Academy' => 'PCACD_Everyone',
        'Putnam City Early Childhood Center' => 'PCECC_Everyone',
        'Putnam City High School' => 'PCH_Everyone',
        'Putnam City North High School' => 'PCN_Everyone',
        'Putnam City West High School' => 'PCW_Everyone',
        'Rollingwood Elementary' => 'RES_Everyone',
        'Tulakes Elementary' => 'TES_Everyone',
        'Windsor Hills Elementary' => 'WHES_Everyone',
        'Western Oaks Elementary' => 'WOES_Everyone',
        'Western Oaks Middle School' => 'WOMS_Everyone',
        'Wiley Post Elementary' => 'WPES_Everyone',
        'Will Rogers Elementary' => 'WRES_Everyone',
    ];

    public function index()
    {
        $this->canAccess();
        $user = strtolower(auth()->user()->uid);
        //$user = 'ahoggatt';
        $schoolMap = $this->schoolMap;
        $schoolNames = $this->getSchoolNames();
        return view('rehire-base', compact('user', 'schoolMap', 'schoolNames'));
    }

    private function canAccess($bypass = false)
    {
        if($bypass) return $this;
        $groups = json_decode(auth()->user()->groups);
        if(!in_array('workflow_users', $groups) && !in_array('DOTPCAdmin', $groups)) {
            return abort(403, "You are not authorized to view the rehire site.");
        }
        return $this;
    }

    private function getSchoolNames()
    {
        $groups = json_decode(auth()->user()->groups);
        $schoolNames = [];
        foreach ($this->schoolMap as $schoolName => $adGroup) {
            foreach($groups as $group){
                if($group === $adGroup){
                    $schoolNames[] = $schoolName;
                }
            }
        }
        return $schoolNames;
    }
}
