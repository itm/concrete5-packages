/*
 * validate add/edit field data
 */
ccmValidateBlockForm = function() {

	if ($('#topic')[0].value.length == 0)
	{
		ccm_addError(ccm_t('title-required'));		
	}

	invalidTutor = ThesisEntry.detectInvalidEntry('tutor')
	if (invalidTutor > -1)
	{
		ccm_addError(ccm_t('invalid_tutor') + (invalidTutor+1));
		return false;
	}
	
	invalidSupervisor = ThesisEntry.detectInvalidEntry('supervisor')
	if (invalidSupervisor > -1)
	{
		ccm_addError(ccm_t('invalid_supervisor') + (invalidSupervisor+1));
		return false;
	}
	
	$('#tutorsJson').val(ThesisData.serializeTutors());
	$('#supervisorsJson').val(ThesisData.serializeSupervisors());
	
	return false;
}

/*
 * Handles JavaScript actions on LDAP list entries within the add/edit view
 * (entries consists of a select and a textfield).
 * 
 * Actions: show/hide custom name field
 */
var ThesisEntry = 
{
	DEFAULT_LDAP_PREFIX: 'ldap:',
	TYPE_TUTOR: 'tutor',
	TYPE_SUPERVISOR: 'supervisor',
	renderLdapOptionValues: function(selValue)
	{
		htmlOptions = '';
		jQuery.each(ThesisData.LDAP_USERS, function(k, v)
		{
			htmlOptions += '<option value="' + k + '"';
			if (k == selValue)
			{
				htmlOptions += ' selected="selected"';
			}
			htmlOptions += '>' + v + '</option>' + "\n";
		});
		
		return htmlOptions;
	},
	renderMove: function(type, index, html)
	{
		list = this.getListByType(type);
		linkUp = '<a href="#" onclick="ThesisEntry.moveItem(\'' + type + '\', ' + index + ', -1); return false;">' + ThesisData.ICON_UP + '</a>';
		linkDown = '<a href="#" onclick="ThesisEntry.moveItem(\'' + type + '\', ' + index + ', 1); return false;">' + ThesisData.ICON_DOWN + '</a>';
		if (index - 1 < 0)
		{
			linkUp = ThesisData.ICON_UP_DISABLED;
		}
		if (index + 1 > list.length - 1)
		{
			linkDown = ThesisData.ICON_DOWN_DISABLED;
		}
		
		htmlDiv = '<div><div style="float: left; width: 11px;">';
		htmlDiv += '<div style="height: 16px; vertical-align: middle"><div style="margin-top: 5px; margin-bottom: 4px">' + linkUp + '</div><div>' + linkDown + '</div></div></div>';
		htmlDiv += '<div style="margin-left: 16px;">' + html + '</div>';
		htmlDiv += '</div>';
		
		return htmlDiv;
	},
	renderLdap: function(type, index, key)
	{
		if (!this.hasLdapUsers())
		{
			return this.renderRaw(type, index, '');
		}
		idAndName = type + '_' + index;
		htmlSelect = '<select onchange="ThesisEntry.updateItems(\'' + type + '\');" style="width: 200px" id="' + idAndName + '" name="' + idAndName + '">';
		htmlSelect += this.renderLdapOptionValues(key);
		htmlSelect += '</select>';
		
		return htmlSelect;
	},
	renderRaw: function(type, index, value)
	{
		idAndName = type + '_' + index;
		htmlText = '<input onchange="ThesisEntry.updateItems(\'' + type + '\'); " style="width: 190px" type="text" id="' + idAndName + '" name="' + idAndName + '" value="' + value + '"/>';
		
		return htmlText;
	},
	renderRemove: function(type, index)
	{
		return '<a href="#" onclick="ThesisEntry.updateItems(\'' + type + '\'); ThesisEntry.removeItem(\'' + type + '\', ' + index + '); return false;">' + ThesisData.ICON_REMOVE + '</a>';
	},
	renderEditMode: function(type, index)
	{
		if (!this.hasLdapUsers())
		{
			return '';
		}
		return '<a href="#" onclick="ThesisEntry.updateItems(\'' + type + '\'); ThesisEntry.switchEditMode(\'' + type + '\', ' + index + '); return false;">' + ThesisData.ICON_SWITCH + '</a>';
	},
	renderList: function(type)
	{
		list = this.getListByType(type);
		
		htmlDiv = '<div id="' + type + '">';
		
		for (var i = 0; i < list.length; i++)
		{
			id = type + '_' + i;
			htmlDiv += '<div class="clearfix" style="margin-bottom: 4px">';
			elem = '';
			if (this.isLdapEntry(list[i]))
			{
				elem += this.renderLdap(type, i, list[i]);
			}
			else
			{
				elem += this.renderRaw(type, i, list[i]);
			}
			
			elem += '&nbsp;' + this.renderRemove(type, i);
			elem += '&nbsp;' + this.renderEditMode(type, i, list[i]);
			htmlDiv += this.renderMove(type, i, elem);
			htmlDiv += '</div>';
		}
		
		return htmlDiv;
	},
	switchEditMode: function(type, index)
	{
		if (!this.hasLdapUsers())
		{
			return;
		}
		
		elem = $('#' + type + '_' + index);
		isSelect = elem.is('select');
		
		if (isSelect)
		{
			elem.replaceWith(this.renderRaw(type, index, ''));
		}
		else
		{
			elem.replaceWith(this.renderLdap(type, index, ''));
		}
		elem.focus();
	},
	addItem: function(type)
	{
		list = this.getListByType(type);
		if (this.hasLdapUsers())
		{
			list.push('ldap:none');
		}
		else
		{
			list.push('');
		}
		
		$('#' + type).replaceWith(this.renderList(type));
	},
	updateItems: function(type)
	{
		list = this.getListByType(type);
		for (i = 0; i < list.length; i++)
		{
			elem = $('#' + type + '_' + i);
			list[i] = elem.val();
		}
	},
	getListByType: function(type)
	{
		if (type == ThesisEntry.TYPE_TUTOR)
		{
			return ThesisData.tutors;
		}
		else
		{
			return ThesisData.supervisors;
		}
	},
	removeItem: function(type, index)
	{
		list = this.getListByType(type);
		list.splice(index, 1);
		$('#' + type).replaceWith(this.renderList(type));
	},
	moveItem: function(type, index, direction)
	{
		list = this.getListByType(type);
		if (index + direction < 0 || index + direction > list.length - 1)
		{
			return;
		}
		
		tmpElem = list[index];
		list[index] = list[index + direction];
		list[index + direction] = tmpElem;
		
		$('#' + type).replaceWith(this.renderList(type));
	},
	isLdapEntry: function(name)
	{
		return name.substr(0, ThesisEntry.DEFAULT_LDAP_PREFIX.length) == ThesisEntry.DEFAULT_LDAP_PREFIX;
	},
	detectInvalidEntry: function(type)
	{
		list = this.getListByType(type);
		invalid = -1;
		for (i = 0; i < list.length; i++)
		{
			elem = $('#' + type + '_' + i);
			isSelect = elem.is('select');
			if (isSelect && elem.val() == ThesisEntry.DEFAULT_LDAP_PREFIX + 'none')
			{
				invalid = i;
				break;
			}
			
			if (!isSelect && elem.val() == '')
			{
				invalid = i;
				break;
			}
		}
		return invalid;
	},
	hasLdapUsers: function()
	{
		var i = 0;
		jQuery.each(ThesisData.LDAP_USERS, function(k, v)
		{
			i++;
		});
		
		return i > 1;
	}
}