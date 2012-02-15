<?php

defined('C5_EXECUTE') or die("Access Denied.");

define('ITM_THESIS_LDAP_PREFIX', 'ldap:');

/**
 * Helper class for the concrete5 itm_thesis package.
 */
class ItmCoursesHelper
{
	/**
	 * Checks whether a given name is a LDAP uid or not. This depends on the
	 * thesis specific assumption that the name begins with
	 * ITM_THESIS_LDAP_PREFIX (which is defaulty set to 'ldap:').
	 * 
	 * @param string $name The name to check
	 * @return bool true if it is a LDAP uid, otherwise false 
	 */
	public function isLdapName($name)
	{
		return strpos($name, ITM_THESIS_LDAP_PREFIX) === 0 || $name == '';
	}
}