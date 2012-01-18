<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

define('ITM_THESIS_LDAP_PREFIX', 'ldap:');

Loader::model('page_list');

class ItmThesisEntryBlockController extends BlockController
{
	protected $btTable = 'btItmThesis';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";

	public function getBlockTypeDescription()
	{
		return t("Adds a thesis entry to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Thesis Entry");
	}

	public function save($data)
	{
		if (!strlen($data['supervisor']))
		{
			$data['supervisor'] = $data['supervisor_ldap'] == 'none' ? '' : $data['supervisor_ldap'];
		}
		
		if (!strlen($data['tutor']))
		{
			$data['tutor'] = $data['tutor_ldap'] == 'none' ? '' : $data['tutor_ldap'];
		}
		
		parent::save($data);
	}

	public function getJavaScriptStrings()
	{
		// return translated strings available for Java Script
		return array(
			'title-required' => t('Please enter a thesis topic.'),
			'invalid_supervisor' => t('Supervisor name is invalid.'),
			'invalid_tutor' => t('Tutor name is invalid.')
		);
	}

	public function isLdapName($name)
	{
		return strpos($name, ITM_THESIS_LDAP_PREFIX) === 0 || $name == '';
	}

	public function isLdapSupervisor()
	{
		return $this->isLdapName($this->supervisor);
	}

	public function isLdapTutor()
	{
		return $this->isLdapName($this->tutor);
	}

	public function getSupervisorName()
	{
		if ($this->isLdapSupervisor())
		{
			return substr($this->supervisor, strlen(ITM_THESIS_LDAP_PREFIX));
		}
		else
		{
			return $this->supervisor;
		}
	}

	public function getTutorName()
	{
		if ($this->isLdapTutor())
		{
			return substr($this->tutor, strlen(ITM_THESIS_LDAP_PREFIX));
		}
		else
		{
			return $this->tutor;
		}
	}

	public function getLdapUsers()
	{
		if (!$this->hasItmLdap())
		{
			return array();
		}

		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		$result = array('none' => t('None'));
		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			$result['ldap:' . $user->uName] = $user->uName;
		}
		return $result;
	}

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