<?php defined('C5_EXECUTE') or die("Access Denied.");

class LdapAuthenticatorHelper {
	
	static public function getFilter() {
		$config = new Config;
		$config->setPackageObject(Package::getByHandle('ldap_auth'));
		$filter = $config->get('LDAP_FILTER');
		//if(empty($filter)) $filter = '(objectCategory=person)';
		return $filter;
	}
	
	public function query($uName, $uPassword, $filter, $base = 'LDAP_BASE_STAFF') {
		$ldap = NewADOConnection('ldap');
		global $LDAP_CONNECT_OPTIONS;
		$LDAP_CONNECT_OPTIONS = Array(
			Array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE"=>2),
			Array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE"=>100),
			Array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE"=>30),
			Array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE"=>3),
			Array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE"=>13),
			Array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE"=>FALSE),
			Array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE"=>FALSE)
		);
		try {
			$config = new Config;
			$config->setPackageObject(Package::getByHandle('ldap_auth'));
			
			//if(strpos($uName, '@') === false) $uName = $uName.'@'.$config->get('LDAP_DOMAIN_NAME');
			//if($uName == '@') $uName = NULL;
			$uName = "uid=$uName,".$config->get($base);
			$ldap->Connect(
				$config->get('LDAP_HOST'), 
				$uName, 
				$uPassword, 
				$config->get($base)
			);
			$message .= 'Successfully connected and authenticated';
			/* No longer required
			try {
				$ldap->SetFetchMode(ADODB_FETCH_ASSOC);
				$ldap_return = $ldap->GetArray($filter);
				$message = str_replace(' and', ',', $message);
				$message .= ' and completed query';
			} catch (Exception $e) {
				$errors[] = 'Authentication successful, however, query could not be performed.';
			} */
		} catch (Exception $e) {
			$errors[] = 'Could not authenticate.';
		}
		if(!empty($message)) $message .= '!';
		$ldap_server_info = $ldap->ServerInfo();
		if(!empty($ldap_server_info['LDAP_OPT_ERROR_STRING'])) {
			$opt_error = explode(',', $ldap_server_info['LDAP_OPT_ERROR_STRING']);
			foreach($opt_error as $msg) {
				if(!(strpos($msg, 'comment:') === false)) $error = str_replace(' comment: ', '', $msg);
			}
			$errors[] = $error;
		}
		
		if(is_array($errors)) {
			if(in_array('In order to perform this operation a successful bind must be completed on the connection.', $errors)) $errors[] = 'LDAP server does not allow "anonymous" querying.';
		}
		if(is_array($errors)) $errors = array_filter($errors);
		
		$ldap->Close();
		
		//return array('return' => $ldap_return, 'message' => $message, 'errors' => $errors);
		return array('return' => true, 'message' => $message, 'errors' => $errors);
	}
	
	public function login($uName, $uPassword, $base = 'LDAP_BASE_STAFF') {
		$filter = self::getFilter();
		if (empty($filter))
		{
			$filter = '(uid='.$uName.'))';
		}
		else
		{
			$filter = '(&'.self::getFilter().'(uid='.$uName.'))';
		}
		$q = self::query($uName, $uPassword, $filter, $base);

		if (!empty($q['errors']))
		{
			throw new Exception($q['errors'][0]);
		}
		
		/* if(!empty($q['return'])) {
			foreach($q['return'] as $item) {
				self::register($item, $uPassword);
			}
		} */
		
		return new User($uName, $uPassword);
	}
	
	public function getUserData($uName, $uPassword, $user = NULL) {
		if(!$user) $user = $uName;
		return self::query($uName, $uPassword, '(&'.self::getFilter().'(sAMAccountName='.$user.'))');
	}

	public function getUserList($uName, $uPassword) {
		return self::query($uName, $uPassword, '(&'.self::getFilter().'(!(description=Built-in*))(!(name=*Mailbox*))(!(name=*Email*)))');
	}

	public function register($item, $uPassword) {
		$ui = UserInfo::getByUserName($item['sAMAccountName']);
		$data['uName']				= $item['sAMAccountName'];
		$data['uPassword']			= $uPassword;
		$data['uPasswordConfirm']	= $uPassword;
		$data['uEmail']				= $item['mail'];
		if(is_object($ui)) {
			$ui = $ui->update($data);
		} else {
			$ui = UserInfo::register($data);
		}
		self::updateGroups($item);
	}

	public function updateGroups($item) {
		$prefix = Config::get('LDAP_GROUP_IMPORT_PREFIX');
		if(is_array($item['memberOf'])) foreach($item['memberOf'] as $group) {
			$group = explode(',', $group);
			$group = $group[0];
			$group = str_replace('CN=', '', $group);
			if(!(strpos($group, 'Domain Admins') === false)) $groups[] = 'Administrators';
			if($prefix) if(!(strpos($group, $prefix) === false)) $groups[] = str_replace($prefix, '', $group);
		}
		if(is_array($groups)) {
			$groups = array_unique($groups);
			$u = UserInfo::getByUserName($item['sAMAccountName'])->getUserObject();
			foreach($groups as $group) {
				$g = Group::getByName($group);
				if(is_object($g)) $u->enterGroup($g);
			}
		}
	}
} ?>
