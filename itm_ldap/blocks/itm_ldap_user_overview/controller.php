<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmLdapUserOverviewBlockController extends BlockController
{
	protected $btTable = 'btItmLdapUserOverview';
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "200";
	protected $btWrapperClass = 'ccm-ui';
	
	public function getBlockTypeDescription()
	{
		return t("Adds a list of users to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM LDAP User Overview");
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
		
		$group = Group::getByName($this->groupName);
		if (empty($group))
		{
			return array();
		}
		$gId = $group->getGroupID();

		Loader::model('user_list');

		$userList = new UserList();
		$userList->sortBy('uName', 'asc');
		$userList->showInactiveUsers = true;
		$userList->showInvalidatedUsers = true;
		$userList->filterByGroupID($gId);

		return $userList->get();
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