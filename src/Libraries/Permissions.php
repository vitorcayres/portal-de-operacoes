<?php

namespace App\Libraries;

class Permissions
{
	public function has_perm($list_perms, $perm) {
		if (count($list_perms) > 0) {
			foreach ($list_perms as $perms) {
				if (isSet($perms->name) && $perms->name == $perm) {
					return true;
					break;
				}
			}
		}
		return false;
	}  
}