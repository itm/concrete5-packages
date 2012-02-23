<?php

defined('C5_EXECUTE') or die("Access Denied.");

define('ITM_COURSES_LDAP_PREFIX', 'ldap:');

/**
 * Helper class for the concrete5 itm_thesis package.
 */
class ItmCoursesHelper
{
	/**
	 * Checks whether a given name is a LDAP uid or not. This depends on the
	 * thesis specific assumption that the name begins with
	 * ITM_COURSES_LDAP_PREFIX (which is defaulty set to 'ldap:').
	 * 
	 * @param string $name The name to check
	 * @return bool true if it is a LDAP uid, otherwise false 
	 */
	public function isLdapName($name)
	{
		return strpos($name, ITM_COURSES_LDAP_PREFIX) === 0 || $name == '';
	}
	
	public function getCourseGroups()
	{
		$db = Loader::db();
		$query = 'SELECT * FROM itmcoursesgroups ORDER BY name ASC';
		$r = $db->query($query);
		$result = array();
		while ($row = $r->fetchRow())
		{
			$result[$row['handle']] = new ItmCourseGroup($row['itmCGID'], $row['handle'], $row['name']);
		}
		return $result;
	}
	
	public function getCourseGroupByHandle($handle)
	{
		$db = Loader::db();
		$query = 'SELECT * FROM itmcoursesgroups WHERE handle LIKE ?';
		$r = $db->query($query, array($handle));
		
		if ($row = $r->fetchRow())
		{
			return new ItmCourseGroup($row['itmCGID'], $row['handle'], $row['name']);
		}
		return null;
	}


	public function deleteCourseGroup($handle)
	{
		$db = Loader::db();
		$q = 'DELETE FROM itmcoursesgroups WHERE handle LIKE ?';
		$r = $db->query($q, array($handle));
	}
	
	public function addCourseGroup($handle, $name)
	{
		$db = Loader::db();
		$q = 'INSERT INTO itmcoursesgroups (handle, name) VALUES (?, ?)';
		$r = $db->query($q, array($handle, $name));
	}
}

class ItmCourseGroup
{
	public $itmCGID;
	public $handle;
	public $name;
	
	public function __construct($itmCGID, $handle, $name)
	{
		$this->itmCGID = $itmCGID;
		$this->handle = $handle;
		$this->name = $name;
	}
	
	public function __toString()
	{
		return empty($this->name) ? '' : $this->name;
	}
}