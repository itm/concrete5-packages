<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmLdapUserOverviewBlockController extends BlockController
{
	protected $btTable = 'btItmLdapUserOverview';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";

	public function getBlockTypeDescription()
	{
		return t("Adds a list of users to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM LDAP User Overview");
	}

	// is called during page view and adds custom stylesheet
	public function on_page_view()
	{
		$bt = BlockType::getByHandle($this->btHandle);
		$uh = Loader::helper('concrete/urls');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'. $uh->getBlockTypeAssetsURL($bt, 'style.css') .'" />');
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
	
	public function getGroupMembers()
	{
		if (!$this->hasUsers())
		{
			return array();
		}

		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		$result = array();
		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			
			$staffGroup = $user->getAttribute('staff_group');
			if (empty($this->groupName) || !empty($staffGroup) && $staffGroup == $this->groupName)
			{
				$name = $user->getAttribute('name');
				$names = explode(' ', $name);
				$result[$names[count($names)-1]] = $user;
			}
		}
		
		ksort($result);
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