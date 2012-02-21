var CourseItem =
{
	switchIcon: 'Switch',
	items: [],
	editMode: false,
	renderOptionList: function(selValue)
	{
		htmlOptions = '';
		jQuery.each(this.items, function(i, v)
		{
			htmlOptions += '<option value="' + v + '"';
			if (v == selValue)
			{
				htmlOptions += ' selected="selected"';
			}
			htmlOptions += '>' + v + '</option>' + "\n";
		});
		
		return htmlOptions;
	},
	renderSelect: function(selValue)
	{
		htmlSelect = '<select style="width: 80%" id="title" name="title">';
		htmlSelect += this.renderOptionList(selValue);
		htmlSelect += '</select>';
		return htmlSelect;
	},
	renderRaw: function(value)
	{
		htmlText = '<input style="width: 80%" type="text" id="title" name="title" value="' + value + '"/>';
		return htmlText;
	},
	renderEditMode: function()
	{
		return '<a href="#" onclick="CourseItem.switchEditMode(); return false;">' + this.switchIcon + '</a>';
	},
	switchEditMode: function()
	{
		this.editMode = !this.editMode;
		if (this.editMode)
		{
			$('#title').replaceWith(this.renderRaw(''));
		}
		else
		{
			$('#title').replaceWith(this.renderSelect(''));
		}
		$('#title').focus();
	},
	renderTitleField: function(selValue)
	{
		html = '';

		if (selValue == '')
		{
			if (this.editMode)
			{
				html += this.renderRaw('');
			}
			else
			{
				html += this.renderSelect('');
			}
		}
		else
		{
			found = false;
			for (var i = 0; i < this.items.length; i++)
			{
				if (this.items[i] == selValue)
				{
					found = true;
					break;
				}
			}
			if (found)
			{
				html += this.renderSelect(selValue);
			}
			else
			{
				html += this.renderRaw('');
			}
		}
		
		html += '&nbsp;' + this.renderEditMode();
		
		return html;
	}
}