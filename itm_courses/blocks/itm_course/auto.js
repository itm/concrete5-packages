/*
 * validate add/edit field data
 */
ccmValidateBlockForm = function() {

	if ($('#topic')[0].value.length == 0)
	{
		ccm_addError(ccm_t('title-required'));		
	}

	if ($('#supervisorRaw').length)
	{
		if (!LdapEntry.isHidden('supervisorRaw') && $('#supervisorRaw').children().filter('input').val().substr(0, LdapEntry.DEFAULT_LDAP_PREFIX.length) == LdapEntry.DEFAULT_LDAP_PREFIX)
		{
			ccm_addError(ccm_t('invalid_supervisor'));
		}
	}
	
	return false;
}

/*
 * Handles JavaScript actions on LDAP list entries within the add/edit view
 * (entries consists of a select and a textfield).
 * 
 * Actions: show/hide custom name field
 */
var LdapEntry = 
{
	DEFAULT_LDAP_PREFIX: 'ldap:',
	/*
	 * Does the main work - sets value and state (shown/hidden) of the textfield
	 * and enables/disables select field if textfield is hidden/shown.
	 */
	switchState: function(divId, state, value)
	{
		var selDiv = $('#'+divId+'Ldap');
		var txtDiv = $('#'+divId+'Raw');
		
		txtDiv.children().filter('input').val(value);
		selDiv.children().filter('select').get(0).disabled = !(state == 'none');
		
		txtDiv.css('display', state);
		
	},
	/*
	 * Pass valid value to switchState() to show textfield
	 */
	showEntry: function(divId, value)
	{
		this.switchState(divId, 'block', value);
	},
	/*
	 * Pass valid value to switchState() to hide textfield
	 */
	hideEntry: function(divId, value)
	{
		this.switchState(divId, 'none', value);
	},
	/*
	 * Switches the state depending on isHidden(divId)
	 */
	switchEntry: function(divId, showVal, hideVal)
	{
		if (this.isHidden(divId + 'Raw'))
		{
			this.showEntry(divId, showVal);
		}
		else
		{
			this.hideEntry(divId, hideVal);
		}
	},
	/*
	 * Checks whether given div is hidden
	 */
	isHidden: function(divId)
	{
		return $('#'+divId).css('display') == 'none';
	}
}