<?php

namespace App\Handlers;

use Adldap\Models\User as LdapUser;
use App\User as User;

class LdapAttributeHandler
{
    /**
     * Returns the group names of each user to the Adldap2 sync attributes.
     * @param User $user
     * @return string
     */
    public function handle(LdapUser $ldapUser, User $user)
    {
        $groupList = $ldapUser->getGroupNames();
        $user->groups = json_encode($groupList);
        $user->account_type = 'Active Directory';
        foreach ($groupList as $key => $value) {
            //Set principal status, if principal
            if ($value == 'DotPCPrinc') {
                $user->pc_principal = 'yes';
            }

            //set home school
            if (preg_match('/_Everyone/', $value)) {
                $user->home_school = $value;
            }
        }
    }
}
