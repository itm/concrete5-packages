/*
 * validate add/edit field data
 */
ccmValidateBlockForm = function()
{
	invalidLecturer = CourseEntry.detectInvalidEntry('lecturer')
	if (invalidLecturer > -1)
	{
		ccm_addError(ccm_t('invalid_lecturer') + (invalidLecturer+1));
		return false;
	}
	
	invalidAssistant = CourseEntry.detectInvalidEntry('assistant')
	if (invalidAssistant > -1)
	{
		ccm_addError(ccm_t('invalid_assistant') + (invalidAssistant+1));
		return false;
	}
	
	$('#lecturersJson').val(CourseData.serializeLecturers());
	$('#assistantsJson').val(CourseData.serializeAssistants());
	
	return false;
}

/*
 * Handles JavaScript actions on lecturer and assistant lists.
 */
var CourseEntry = 
{
	DEFAULT_LDAP_PREFIX: 'ldap:',
	TYPE_LECTURER: 'lecturer',
	TYPE_ASSISTANT: 'assistant',
	renderLdapOptionValues: function(selValue)
	{
		htmlOptions = '';
		jQuery.each(CourseData.LDAP_USERS, function(k, v)
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
		linkUp = '<a href="#" onclick="CourseEntry.moveItem(\'' + type + '\', ' + index + ', -1); return false;">' + CourseData.ICON_UP + '</a>';
		linkDown = '<a href="#" onclick="CourseEntry.moveItem(\'' + type + '\', ' + index + ', 1); return false;">' + CourseData.ICON_DOWN + '</a>';
		if (index - 1 < 0)
		{
			linkUp = CourseData.ICON_UP_DISABLED;
		}
		if (index + 1 > list.length - 1)
		{
			linkDown = CourseData.ICON_DOWN_DISABLED;
		}
		
		htmlDiv = '<div><div style="float: left; width: 11px;">';
		htmlDiv += '<div style="height: 16px; vertical-align: middle"><div style="margin-top: 5px; margin-bottom: 4px">' + linkUp + '</div><div>' + linkDown + '</div></div></div>';
		htmlDiv += '<div style="margin-left: 16px;">' + html + '</div>';
		htmlDiv += '</div>';
		
		return htmlDiv;
	},
	renderLdap: function(type, index, key)
	{
		if (CourseData.LDAP_USERS.length == 0)
		{
			return this.renderRaw(type, index, '');
		}
		idAndName = type + '_' + index;
		htmlSelect = '<select onchange="CourseEntry.updateItems(\'' + type + '\');" style="width: 200px" id="' + idAndName + '" name="' + idAndName + '">';
		htmlSelect += this.renderLdapOptionValues(key);
		htmlSelect += '</select>';
		
		return htmlSelect;
	},
	renderRaw: function(type, index, value)
	{
		idAndName = type + '_' + index;
		htmlText = '<input onchange="CourseEntry.updateItems(\'' + type + '\'); " style="width: 190px" type="text" id="' + idAndName + '" name="' + idAndName + '" value="' + value + '"/>';
		
		return htmlText;
	},
	renderRemove: function(type, index)
	{
		return '<a href="#" onclick="CourseEntry.updateItems(\'' + type + '\'); CourseEntry.removeItem(\'' + type + '\', ' + index + '); return false;">' + CourseData.ICON_REMOVE + '</a>';
	},
	renderEditMode: function(type, index)
	{
		if (CourseData.LDAP_USERS.length == 0)
		{
			return '';
		}
		return '<a href="#" onclick="CourseEntry.updateItems(\'' + type + '\'); CourseEntry.switchEditMode(\'' + type + '\', ' + index + '); return false;">' + CourseData.ICON_SWITCH + '</a>';
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
		if (CourseData.LDAP_USERS.length == 0)
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
		if (CourseData.LDAP_USERS.length > 0)
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
		if (type == CourseEntry.TYPE_LECTURER)
		{
			return CourseData.lecturers;
		}
		else
		{
			return CourseData.assistants;
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
		return name.substr(0, CourseEntry.DEFAULT_LDAP_PREFIX.length) == CourseEntry.DEFAULT_LDAP_PREFIX;
	},
	detectInvalidEntry: function(type)
	{
		list = this.getListByType(type);
		invalid = -1;
		for (i = 0; i < list.length; i++)
		{
			elem = $('#' + type + '_' + i);
			isSelect = elem.is('select');
			if (isSelect && elem.val() == CourseEntry.DEFAULT_LDAP_PREFIX + 'none')
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
	}
}