<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('user_list');

class ItmLdapUserTuple
{
	public $first;
	public $second;

	public function __construct($first, $second)
	{
		$this->c5User = $first;
		$this->second = $second;
	}

}

class DashboardItmLdapController extends Controller
{

	public function view()
	{
		if (!$this->hasLdapAuth())
		{
			$this->set('dispatchTo', 'noldapauth');
			return;
		}
	}

	public function hasLdapAuth()
	{
		$ldapAuthPkg = Package::getByHandle('ldap_auth');
		return!empty($ldapAuthPkg);
	}

// this is for ajax request purposes only!
// returns a JSON object containing all LDAP users
// currently stored in the Concrete5 database
	public function ajax_list_staff()
	{
		
	}

	public function synchronize()
	{
		if (!$this->hasLdapAuth())
		{
			$this->redirect('/dashboard/itm_ldap');
		}

		$c5LdapUsers = $this->getLdapStaffFromC5();
		$ldapLdapUsers = $this->getLdapStaff();

//		$c5LdapUsers = array(
//			array('uid' => 'david'),
//			array('uid' => 'jo'),
//			array('uid' => 'ronny'),
//			array('uid' => 'richard'),
//			array('uid' => 'lukas')
//		);
//
//		$ldapLdapUsers = array(
//			array('uid' => 'sergej'),
//			array('uid' => 'ronny'),
//			array('uid' => 'david')
//		);


		$resultSet = $this->intersect($c5LdapUsers, $ldapLdapUsers);
		$this->mergeAssocArray($resultSet, $this->subtract($c5LdapUsers, $ldapLdapUsers));
		$this->mergeAssocArray($resultSet, $this->subtract($ldapLdapUsers, $c5LdapUsers));

		$this->set('userlist', $resultSet);

		$this->set('dispatchTo', 'synchronize');
	}

	protected function mergeAssocArray(&$arr1, &$arr2)
	{
		foreach ($arr2 as $key => $value)
		{
			$arr1[$key] = $value;
		}
	}

	public function getLdapStaffFromC5()
	{



		$group = Group::getByName('ldap');
		$gId = $group->getGroupID();

		$userList = new UserList();
		$userList->sortBy('uName', 'asc');
		$userList->showInactiveUsers = true;
		$userList->showInvalidatedUsers = true;
		$userList->filterByGroupID($gId);

		return $userList->get();
	}

	public function getLdapStaff($ldapBind = false)
	{
		$ldap = $ldapBind;
		if (!$ldap)
		{
			$ldap = $this->ldapBindStaff();
		}

		if (!$ldap)
		{
			throw new Exception(t('LDAP connection failed!'));
		}

		$result = $ldap->GetArray('(uid=*)');

		$ldap->Close();

		return $result;
	}

// fetch new LDAP users from LDAP servers
// which means all users currently not persisted in the Concrete5 database
	public function update_new_users()
	{
		if (!$this->hasLdapAuth())
		{
			$this->redirect('/dashboard/itm_ldap');
		}

		$ldap = $this->ldapBindStaff();

		$staffFromC5 = $this->getLdapStaffFromC5();
		$staffFromLdapServer = $this->getLdapStaff();

		$this->subtract($staffFromLdapServer, $staffFromC5);

		if (!count($staffFromC5))
		{
			$this->clean();
		}

		foreach ($staffFromLdapServer as $item)
		{
			
		}
	}

// does $set1 \cap B
// stores elements of both sets in an array (<itemFromSet1>, <itemFromSet2>)
//always based on user name (uName/uid)
	protected function intersect($set1, $set2)
	{
		$resultSet = array();
		foreach ($set1 as $set1Item)
		{
			$set1ItemUName = $this->resolveUsername($set1Item);
			foreach ($set2 as $set2Item)
			{
				$set2ItemUName = $this->resolveUsername($set2Item);
				if ($set1ItemUName == $set2ItemUName)
				{
					$resultSet[$set1ItemUName] = new ItmLdapUserTuple($set1Item, $set2Item);
					break;
				}
			}
		}

		return $resultSet;
	}

	private function resolveUsername($item)
	{
		if ($item instanceof UserInfo)
		{
			return $item->getUserName();
		}
		elseif ($item instanceof ItmLdapUserTuple)
		{
			return $this->resolveUsername($item->first);
		}
		else
		{
			return $item['uid'];
		}
	}

	// does $set1 \ $set2
	// returns objects from type given in $set1
	protected function subtract($set1, $set2)
	{
		$resultSet = array();
		foreach ($set1 as $set1Item)
		{
			$found = false;
			$set1ItemUName = $this->resolveUsername($set1Item);
			foreach ($set2 as $set2Item)
			{
				$set2ItemUName = $this->resolveUsername($set2Item);
				$uName = $this->resolveUsername($set1Item);
				if ($set1ItemUName == $set2ItemUName)
				{
					$found = true;
					break;
				}
			}

			if (!$found)
			{
				$resultSet[$set1ItemUName] = $set1Item;
			}
		}

		return $resultSet;
	}

	// update designated user
	public function update_user()
	{
		$val = Loader::helper('validation/error');

		$uName = $this->post('uid');

		$ldapUser = $this->getLdapUser($uName);
		if (empty($ldapUser))
		{
			$val->add(t('LDAP user does not exist. Update process aborted.'));
			$this->set('error', $val);
			return;
		}

		try
		{
			$this->addUserFromLdap($ldapUser);
		}
		catch (Exception $e)
		{
			$val->add($e->getMessage());
			$this->set('error', $val);
			$this->synchronize();
			return;
		}
		$this->set('message', 'User successfully inserted.');
		$this->synchronize();
	}

	public function addUserFromLdap($ldapUser)
	{
		$group = Group::getByName('ldap');
		$gId = $group->getGroupID();
		
		if (!$gId)
		{
			throw new Exception("Required group named ldap not found. Update process aborted.");
		}
		
		$userInfo = UserInfo::getByUserName($ldapUser['uid']);
		if (empty($userInfo))
		{
			$data = array(
				'uName' => $ldapUser['uid'],
				'uPassword' => '',
				'uEmail' => $ldapUser['mail'],
				'uIsActive' => 1
			);
			$userInfo = UserInfo::add($data);
		}

		if (empty($userInfo))
		{
			throw new Exception('Inserting LDAP user in concrete5 database failed. Update process aborted.');

		}

		$userInfo->updateGroups(array($gId));

		if (isset($ldapUser['telephoneNumber']))
		{
			$userInfo->setAttribute('telephone_number', $ldapUser['telephoneNumber']);
		}

		if (isset($ldapUser['roomNumber']))
		{
			$userInfo->setAttribute('room_number', $ldapUser['roomNumber']);
		}
	}

	public function remove_user()
	{
		echo "REMOVED";
	}

// remove LDAP users from Concrete5 user list
	public function clear()
	{
		
	}

// refresh all LDAP user settings from LDAP server
	public function clean()
	{
		
	}

	public static function ldapBindStaff()
	{
		$ldap = NewADOConnection('ldap');
		global $LDAP_CONNECT_OPTIONS;
		$LDAP_CONNECT_OPTIONS = array(
			array("OPTION_NAME" => LDAP_OPT_DEREF, "OPTION_VALUE" => 2),
			array("OPTION_NAME" => LDAP_OPT_SIZELIMIT, "OPTION_VALUE" => 100),
			array("OPTION_NAME" => LDAP_OPT_TIMELIMIT, "OPTION_VALUE" => 30),
			array("OPTION_NAME" => LDAP_OPT_PROTOCOL_VERSION, "OPTION_VALUE" => 3),
			array("OPTION_NAME" => LDAP_OPT_ERROR_NUMBER, "OPTION_VALUE" => 13),
			array("OPTION_NAME" => LDAP_OPT_REFERRALS, "OPTION_VALUE" => false),
			array("OPTION_NAME" => LDAP_OPT_RESTART, "OPTION_VALUE" => false)
		);

		if (!$ldap->Connect('ldap.itm.uni-luebeck.de', '', '', 'ou=Staff,ou=People,dc=itm,dc=uni-luebeck,dc=de'))
		{
			throw new Exception(t('LDAP connection failed!'));
		}

		$ldap->SetFetchMode(ADODB_FETCH_ASSOC);

		return $ldap;
	}

	public function getCachedUsers()
	{
		$ldap = $this->ldapBindStaff();
		if (!$ldap)
		{
			throw new Exception(t('LDAP connection failed!'));
		}

		$filter = '(uid=*)';

		$result = $ldap->GetArray($filter);

		$ldap->close();

		return $result;
	}

	public function update()
	{
		$ldap = $this->ldapBindStaff();
		if (!$ldap)
		{
			throw new Exception(t('LDAP connection failed!'));
		}

		$GLOBALS['ldap_return'] = $ldap->GetArray('(uid=*)');

		$ldap->Close();
	}

	public function getLdapUser($uid, $ldapBind = false)
	{
		$ldap = $ldapBind;
		if (!$ldap)
		{
			$ldap = $this->ldapBindStaff();
		}

		$result = $ldap->GetArray('(uid=' . $uid . ')');

		if (!$ldapBind)
		{
			$ldap->Close();
		}

		if (count($result) == 1)
		{
			return $result[0];
		}
		else
		{
			return null;
		}
	}

}

?>