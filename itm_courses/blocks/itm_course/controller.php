<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('page_list');

class ItmCourseBlockController extends BlockController
{
	protected $btTable = 'btItmCourse';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";
	protected $btWrapperClass = 'ccm-ui';
	
	public function getBlockTypeDescription()
	{
		return t("Adds a course to a course page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Course");
	}

	public function save($data)
	{
		$data['lecturers'] = $data['lecturersJson'];
		$data['assistants'] = $data['assistantsJson'];
		$data['groups'] = Loader::helper('json')->encode($data['groups']);
		
		parent::save($data);
	}

	public function getJavaScriptStrings()
	{
		// return translated strings available for Java Script
		return array(
			'invalid_lecturer' => t('At least one lecturer is invalid (none selected or empty field). Take a look at position: '),
			'invalid_assistant' => t('At least one assistant is invalid (none selected or empty field). Take a look at position: ')
		);
	}

	/**
	 * @return array asso. array of UserInfo elements with qualified user names
	 *               as keys (qualified means: ITM_COURSES_LDAP_PREFIX + user
	 *               name)
	 */
	public function getLdapUsers()
	{
		if (!$this->hasItmLdap())
		{
			return array();
		}

		Loader::helper('itm_courses', 'itm_courses');
		
		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		$result = array('ldap:none' => t('None'));
		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			$result[ITM_COURSES_LDAP_PREFIX . $user->uName] = $user->uName;
		}
		return $result;
	}

	public function getCourseGroups()
	{
		if (strlen($this->groups))
		{
			return Loader::helper('json')->decode($this->groups);
		}
		
		return array();
	}
	
	public function getLecturers()
	{
		if (!strlen($this->lecturers))
		{
			return array();
		}
		return Loader::helper('json')->decode($this->lecturers);
	}
	
	public function getAssistants()
	{
		if (!strlen($this->assistants))
		{
			return array();
		}
		return Loader::helper('json')->decode($this->assistants);
	}
	
	public function isLdapName($name)
	{
		return Loader::helper('itm_courses', 'itm_courses')->isLdapName($name);
	}
	
	public function isEmptyName($name)
	{
		return empty($name) || $name == ITM_COURSES_LDAP_PREFIX . 'none';
	}
	
	public function cutLdapPrefix($name)
	{
		if ($this->isLdapName($name))
		{
			return substr($name, strlen(ITM_COURSES_LDAP_PREFIX));
		}
		
		return $name;
	}
	
	public function renderName($name)
	{
		$ldapHelper = Loader::helper('itm_ldap', 'itm_ldap');
		if ($this->isEmptyName($name))
		{
			return '';
		}
		
		if (!$this->isLdapName($name))
		{
			return $name;
		}
		
		$name = $this->cutLdapPrefix($name);
		$ui = UserInfo::getByUserName($name);
		if (!empty($ui))
		{
			$name = $ui->getAttribute('name');
			if (!empty($name))
			{
				$fullName = $ldapHelper->getFullName($ui);
				$link = $ldapHelper->getUserPageLink($name);
				if ($link)
				{
					echo '<a href="' . $link . '">' . $fullName . '</a>';
				}
				else
				{
					echo $fullName;
				}
			}
		}
		return '';
	}
	
	/**
	 * @return bool true if ITM LDAP package is available and at least one LDAP
	 *                   user entry exists, otherwise false
	 */
	public function hasItmLdap()
	{
		$ldapAuthPkg = Package::getByHandle('ldap_auth');
		if (!empty($ldapAuthPkg))
		{
			$ilh = Loader::helper('itm_ldap', 'itm_ldap');
			if ($ilh->hasLdapAuth())
			{
				try
				{
					return count($ilh->getLdapStaffFromC5()) > 0;
				}
				catch (Exception $e)
				{
					return false;
				}
			}
		}

		return false;
	}
	
}

?>