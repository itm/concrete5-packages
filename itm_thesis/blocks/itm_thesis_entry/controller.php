<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('page_list');
Loader::helper('itm_thesis', 'itm_thesis');

class ItmThesisEntryBlockController extends BlockController
{
	protected $btTable = 'btItmThesis';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";
	protected $btWrapperClass = 'ccm-ui';
	
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
		$data['tutor'] = $data['tutorsJson'];
		$data['supervisor'] = $data['supervisorsJson'];
		
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

		Loader::helper('itm_thesis', 'itm_thesis');
		
		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		$result = array(ITM_THESIS_LDAP_PREFIX . 'none' => t('None'));
		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			$result[ITM_THESIS_LDAP_PREFIX . $user->uName] = $user->uName;
		}
		return $result;
	}

	public function getTutors()
	{
		if (!strlen($this->tutor))
		{
			return array();
		}
		
		//legacy fix
		if (substr($this->tutor, 0, 1) != '[')
		{
			return array($this->tutor);
		}
		
		return Loader::helper('json')->decode($this->tutor);
	}
	
	public function getSupervisors()
	{
		if (!strlen($this->supervisor))
		{
			return array();
		}
		
		//legacy fix
		if (substr($this->supervisor, 0, 1) != '[')
		{
			return array($this->supervisor);
		}
		
		return Loader::helper('json')->decode($this->supervisor);
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
	
	public function isLdapName($name)
	{
		return Loader::helper('itm_thesis', 'itm_thesis')->isLdapName($name);
	}
	
	public function isEmptyName($name)
	{
		return empty($name) || $name == ITM_THESIS_LDAP_PREFIX . 'none';
	}
	
	public function cutLdapPrefix($name)
	{
		if ($this->isLdapName($name))
		{
			return substr($name, strlen(ITM_THESIS_LDAP_PREFIX));
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
	
}

?>