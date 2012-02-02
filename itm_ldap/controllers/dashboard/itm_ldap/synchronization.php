<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('user_list');

class DashboardItmLdapSynchronizationController extends Controller
{
	private $helper; // package helper class

	public function __construct()
	{
		parent::__construct();
		$this->helper = Loader::helper('itm_ldap', 'itm_ldap');
	}

	public function view()
	{
		if (!$this->helper->hasLdapAuth())
		{
			// show missing package error
			$this->set('dispatchTo', 'noldapauth');
			return;
		}

		try
		{
			//show overview
			$this->synchronize();
		}
		catch (Exception $e)
		{
			$this->set('msg', t('Reason:') . ' ' . $e->getMessage());
			$this->set('dispatchTo', 'configerror');
			return;
		}
	}

	/**
	 * Fetches users from LDAP and concrete5 and lists them up
	 */
	public function synchronize()
	{
		if (!$this->helper->hasLdapAuth())
		{
			//redirect to default page which will also trigger an error
			$this->redirect('/dashboard/itm_ldap');
		}

		// fetch concrete5 and LDAP users
		$c5LdapUsers = $this->helper->getLdapStaffFromC5();
		$ldapLdapUsers = $this->helper->getLdapStaff();

		// generate intersection and finally merge them with the rest
		// this is done to distinguish between any type of users
		$resultSet = $this->helper->intersect($c5LdapUsers, $ldapLdapUsers);
		$this->helper->mergeAssocArray($resultSet, $this->helper->subtract($c5LdapUsers, $ldapLdapUsers));
		$this->helper->mergeAssocArray($resultSet, $this->helper->subtract($ldapLdapUsers, $c5LdapUsers));

		// sort them ascending
		ksort($resultSet);

		// set result set
		$this->set('userlist', $resultSet);

		// apply filter
		$filter = array(
			'value' => '',
			'c5' => false,
			'ldap' => false
		);
		$this->set('filter', $filter);

		$filter = $this->post('filter');
		if (!empty($filter))
		{
			$json = Loader::helper('json');
			$filter = $json->decode($filter);
			$this->set('filter', array(
				'value' => $filter->value,
				'c5' => $filter->c5,
				'ldap' => $filter->ldap
			));
		}
	}

	/**
	 * Updates a user
	 */
	public function update_user()
	{
		$val = Loader::helper('validation/error');

		$uName = $this->post('uid');

		$ldapUser = $this->helper->getLdapUser($uName);
		if (empty($ldapUser))
		{
			$val->add(sprintf(t("LDAP user <b>%s</b> does not exist. Update process aborted."), $uName));
			$this->set('error', $val);
			return;
		}

		try
		{
			$this->helper->addUserFromLdap($ldapUser);
		}
		catch (Exception $e)
		{
			$val->add($e->getMessage());
			$this->set('error', $val);
			$this->synchronize();
			return;
		}
		$this->set('message', 'User successfully updated.');
		$this->synchronize();
	}

	/**
	 * Updates several users
	 */
	public function update_users()
	{
		$val = Loader::helper('validation/error');
		$json = Loader::helper('json');

		$items = $json->decode($this->post('items'));

		if (!is_array($items))
		{
			$val->add(t('Fatal error: user list is corrupted.'));
			$this->set('error', $val);
			$this->synchronize();
			return;
		}

		foreach ($items as $uName)
		{
			$ldapUser = $this->helper->getLdapUser($uName);
			if (empty($ldapUser))
			{
				$val->add(sprintf(t("LDAP user <b>%s</b> does not exist. Skip this entry."), $uName));
			}

			try
			{
				$this->helper->addUserFromLdap($ldapUser);
			}
			catch (Exception $e)
			{
				$val->add(sprintf(t('Error updating user <b>%s</b>: %s. Skip this entry.'), $uName, $e->getMessage()));
			}
		}

		$this->set('error', $val);
		$this->set('message', 'Users successfully updated.');
		$this->synchronize();
	}

	/**
	 * Removes a user
	 */
	public function remove_user()
	{
		$val = Loader::helper('validation/error');

		$uName = $this->post('uid');

		$user = UserInfo::getByUserName($uName);

		if (empty($user))
		{
			$val->add(t("User <b>$uName</b> does not exist. Deletion process aborted."));
			$this->set('error', $val);
			$this->synchronize();
			return;
		}

		if ($user->delete() === false)
		{
			$val->add(t("User <b>$uName</b> has not been deleted."));
			$this->set('error', $val);
			$this->synchronize();
			return;
		}

		$this->set('message', t('User has been successfully deleted.'));
		$this->synchronize();
	}

	/**
	 * Removes several users
	 */
	public function remove_users()
	{
		$val = Loader::helper('validation/error');
		$json = Loader::helper('json');

		$items = $json->decode($this->post('items'));

		if (!is_array($items))
		{
			$val->add(t('Fatal error: user list is corrupted.'));
			$this->set('error', $val);
			$this->synchronize();
			return;
		}

		foreach ($items as $uName)
		{
			$user = UserInfo::getByUserName($uName);
			if (empty($user))
			{
				continue;
			}

			if ($user->delete() === false)
			{
				$val->add(sprintf(t('Unknown error while deleting user <b>%s</b>. Skip this entry.'), $uName));
			}
		}

		$this->set('error', $val);
		$this->set('message', 'Users successfully deleted.');
		$this->synchronize();
	}

}

?>