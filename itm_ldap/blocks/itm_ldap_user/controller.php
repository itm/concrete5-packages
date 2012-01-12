<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmLdapUserBlockController extends BlockController
{
	protected $btTable = 'btItmLdapUser';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";

	public function getBlockTypeDescription()
	{
		return t("Adds a LDAP user entry to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM LDAP User Entry");
	}

	public function save($data)
	{
		parent::save($data);
	}

	public function view()
	{
		$userInfo = UserInfo::getByUserName($this->uName);
		$this->set('userInfo', $userInfo);
	}
	
	public function getLdapUsers()
	{
		if (!$this->hasUsers())
		{
			return array();
		}

		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			$result[$user->uName] = $user->uName;
		}
		return $result;
	}

	public function hasUsers()
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

}

?>