<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

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
		// save special value for supervior/tutor if none is specified
		
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

	/**
	 * @return bool true if supervisor is from LDAP, otherwise false
	 */
	public function isLdapSupervisor()
	{
		return Loader::helper('itm_thesis', 'itm_thesis')->isLdapName($this->supervisor);
	}

	/**
	 * @return bool true if tutor is from LDAP, otherwise false 
	 */
	public function isLdapTutor()
	{
		return Loader::helper('itm_thesis', 'itm_thesis')->isLdapName($this->tutor);
	}

	/**
	 * Returns the supervisor name excluding the ITM_THESIS_LDAP_PREFIX
	 * (defaultly set to 'ldap:').
	 * 
	 * @return string name without ITM_THESIS_LDAP_PREFIX
	 */
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

	/**
	 * Returns the tutor name excluding the ITM_THESIS_LDAP_PREFIX
	 * (defaultly set to 'ldap:').
	 * 
	 * @return string name without ITM_THESIS_LDAP_PREFIX
	 */
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

	/**
	 * @return array asso. array of UserInfo elements with qualified user names
	 *               as keys (qualified means: ITM_THESIS_LDAP_PREFIX + user
	 *               name)
	 */
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