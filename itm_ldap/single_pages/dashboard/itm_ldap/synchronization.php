<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
// this is only a dispatcher file

switch ($dispatchTo)
{
	case 'noldapauth':
		include_once('noldapauth.php');
		break;
	
	case 'configerror':
		include_once('configerror.php');
		break;
	
	default:
		include_once('synchronize.php');
		break;
}