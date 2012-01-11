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

		/*
		if (!LdapEntry.isHidden('tutorRaw') && $('#tutorRaw').val().substr(0, LdapEntry.DEFAULT_LDAP_PREFIX.length))
		{
			ccm_addError(ccm_t('invalid_tutor'));
		}*/
	}
	
	return false;
}

var LdapEntry = 
{
	DEFAULT_LDAP_PREFIX: 'ldap:',
	switchState: function(divId, state, value)
	{
		var selDiv = $('#'+divId+'Ldap');
		var txtDiv = $('#'+divId+'Raw');
		
		txtDiv.children().filter('input').val(value);
		selDiv.children().filter('select').get(0).disabled = !(state == 'none');
		
		txtDiv.css('display', state);
		
	},
	showEntry: function(divId, value)
	{
		this.switchState(divId, 'block', value);
	},
	hideEntry: function(divId, value)
	{
		this.switchState(divId, 'none', value);
	},
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
	isHidden: function(divId)
	{
		return $('#'+divId).css('display') == 'none';
	}
}