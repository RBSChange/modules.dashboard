// Avoid '$' conflicts with other libraries.
// You must use 'jQuery' instead of '$'.
//jQuery.noConflict();

/**
 * DashboardClass class.
 * Contains the widgets references.
 */
function DashboardClass()
{
	this.MODULE_ACCESSOR = 'module';
	this.ACTION_ACCESSOR = 'action';
	this.controllerUrl = '{UIHOST}/xul_controller.php';
	this.detailWindow = null;

	this.widgets = [ ];


	/**
	 * Registers a widget inside the Dashboard.
	 * @param DashboardWidgetClass widget The DashboardWidgetClass to register.
	 */
	this.registerWidget = function(widget)
	{
		for (var i=0 ; i<this.widgets.length ; i++)
		{
			if (this.widgets[i] == widget) return;
		}
		this.widgets.push(widget);
		
		var index = this.widgets.length - 1;
		widget.element.attr('widgetIndex', index.toString());
		return this;
	}


	/**
	 * Refreshes the dashboard (all the widgets).
	 */
	this.refresh = function()
	{
		for (var i=0 ; i<this.widgets.length ; i++)
		{
			this.widgets[i].refresh();
		}
		return this;
	}


	/**
	 * Opens a new window with the result of a module/action.
	 */
	this.openDetail = function(module, action, parameters, large)
	{
		var windowParams = [ ];
		if (large)
		{
			windowParams['width'] = Math.floor(screen.width * 0.8);
			windowParams['height'] = Math.floor(screen.height * 0.8);
		}
		else
		{
			windowParams['width'] = 800;
			windowParams['height'] = 600;
		}
		wToolkit.dialog(module, action, parameters, windowParams, true, false);
		window.Dashboard.refresh();
	}

	this.closeDetail = function()
	{
		window.close();
	}
	
	this.openTask = function(taskId, dialog, moduleName)
	{
		var wController = parent.window.document.getElementById('wcontroller');
		var module = wController.getModuleByName(moduleName);
		if (module && module.getAttribute('version') == 'v2')
		{
			this.openDetail('task', 'ViewUserTask', { cmpref: taskId }, true);
		}
		else
		{
			wController.openModalDialog(this, dialog, {taskId: taskId});
		}
	}

	/**
	 * Builds a URL to access module/action with the given parameters.
	 */
	this.makeUrl = function(module, action, parameters)
	{
		var url = this.controllerUrl;
		url += '?' + this.MODULE_ACCESSOR + '=' + encodeURIComponent(module);
		url += '&' + this.ACTION_ACCESSOR + '=' + encodeURIComponent(action);
		for (var name in parameters)
		{
			var value = parameters[name];
			// parse arrays
			if (value != null)
			{
				if (typeof(value) == 'object' && 'length' in value)
				{
					for (var i=0; i<value.length; i++)
					{
						url += '&' + name + '[]=' + encodeURIComponent(value[i]);
					}
				}
				else if (typeof(value) != 'function')
				{
					url += '&' + name + '=' + encodeURIComponent(value);
				}
		    }
		}
		return url;
	}

	this.getWidgetsByName = function(module, action)
	{
		var result = [];
		for(var i = 0; i < this.widgets.length ; i++)
		{
			var widget = this.widgets[i];
			if(widget.module == module && widget.action == action)
			{
				result.push(widget)
			}
		}
		return result;
	}
	
	this.getWidgetByNode = function(element)
	{
		while (element != null && !element.hasAttribute('widgetIndex')) 
		{
			element = element.parentNode;
		}
		if (element == null) {return null;}
		var index = parseInt(element.getAttribute('widgetIndex'));
		return this.widgets[index];
	}

}
window.Dashboard = new DashboardClass();


/**
 * DashboardWidgetClass class.
 */
function DashboardWidgetClass(element)
{
	this.module    = null;
	this.refreshURL    = null;
	this.action    = null;
	this.element   = element;
	this.collapsed = false;

	this.parameters = {};

	this.initialize = function()
	{
		if (!this.element.attr('static'))
		{
			this.element.html(
				  '<div class="title-bar">'
				+   '<div class="icon"></div>'
				+   '<div class="title"></div>'
				+   '<div class="buttons-bar">'
				+     '<a class="button" action="gotomodule" href="javascript:;" title="&modules.dashboard.dashboard.GoToModuleEllipsis;"><img src="{UIHOST}/changeicons/small/search.png"/></a>'
				+     '<a class="button" action="refresh" href="javascript:;" title="&modules.dashboard.dashboard.Refresh;"><img src="{UIHOST}/changeicons/small/refresh.png"/></a>'
				+     '<a class="button" action="toggle" href="javascript:;" title="&modules.dashboard.dashboard.Toggle;"><img src="{UIHOST}/changeicons/small/toggle.png"/></a>'
				+   '</div>'
				+ '</div>'
				+ '<div class="content-block">'
				+   '<div class="content">'+this.element.html()+'</div>'
				+ '</div>'
				);
		}

		var parameters = this.element.attr('parameters');
		if (parameters)
		{
		
			var params = parameters.split(';');
			for(var i = 0; i < params.length ; i++)
			{
				
				var paramArray = params[i].split(':');
				if (paramArray.length == 2)
				{
					this.parameters[this.trim(paramArray[0])] = this.trim(paramArray[1]);
				}
			}
		}

		this.contentBlock = this.element.children('.content-block');
		this.contentElm   = this.contentBlock.children('.content');
		this.titleElm     = this.element.find('.title-bar .title');
		this.iconElm      = this.element.find('.title-bar .icon');
		this.buttons      = this.element.find('.title-bar .buttons-bar .button');

		var _this = this;

		this.element.find('.title-bar').dblclick(function() {
			_this.toggle();
			});

		window.Dashboard.registerWidget(this);

		if (this.element.attr('title'))
		{
			this.setTitle(this.element.attr('title'), this.element.attr('icon'));
		}

		if (this.element.attr('refreshURL'))
		{
			this.refreshURL = this.element.attr('refreshURL');
			this.module = this.refreshURL.match(/dashboardParam%5Btype%5D=([^_]*)_/)[1];
		}
		else
		{
			// Finds modules name from the class attribute
			var classArray = this.element.attr('class').split(/\s+/);
			for (var i=0 ; i<classArray.length && this.module == null ; i++)
			{
				if (classArray[i].substring(0, 8) == 'modules_')
				{
					var parts = classArray[i].split('_');
					this.module = parts[1];
					this.action = (parts.length == 3) ? parts[2] : 'Dashboard';
					this.refresh();
				}
			}
		}
		var hideGotoModule = this.element.attr('hideGotoModule') == 'true';
		this.buttons.each(
			function() {
				var href = jQuery(this).attr('action');
				if (href == 'gotomodule' && hideGotoModule)
				{
					jQuery(this).hide();
				}
				else
				{
					jQuery(this).click(function(){_this[href]();});
				}
			} );
	}


	/**
	 * Called when the widget has loaded, used to set the title and content.
	 * @param XmlDocument xmlDoc
	 */
	this.onLoad = function(data)
	{
		// Parse the text string returned by jQuery.get().
		var xmlParser = new DOMParser();
		var xmlDoc = xmlParser.parseFromString(data, 'text/xml');
		// Check whether the response's content is the XML we are expecting:
		// it must contain one (and only one) <title/> element. 
		var isXmlOK = xmlDoc
			&& xmlDoc.getElementsByTagName
			&& xmlDoc.getElementsByTagName('title').length == 1; 
		
		if (isXmlOK)
		{
			this.setTitle(
				xmlDoc.getElementsByTagName('title').item(0).firstChild.nodeValue,
				xmlDoc.getElementsByTagName('icon').item(0).getAttribute('image')
			);
		}
		else
		{
			this.setTitle(
				"&modules.dashboard.dashboard.Loading-Error;" + " (" + this.module + "/" + this.action + ")",
				'{UIHOST}/changeicons/small/unknown.png'
			);
			
		}

		if (isXmlOK && xmlDoc.getElementsByTagName('content').item(0).firstChild)
		{
			this.setContent(xmlDoc.getElementsByTagName('content').item(0).firstChild.nodeValue);
		}
		else
		{
			this.setContent(null);
		}
	}


	/**
	 * Sets the title of the widget.
	 * @param String title
	 * @param String icon
	 * @return DashboardWidgetClass this
	 */
	this.setTitle = function(title, icon)
	{
		this.titleElm.html(title);
		if (icon && icon.length)
		{
			this.iconElm.css('background', 'url('+icon+') no-repeat 0 0').width(18).height(16);
		}
		return this;
	}


	/**
	 * Sets the content of the widget.
	 * @param String content
	 * @return DashboardWidgetClass this
	 */
	this.setContent = function(content)
	{
		if (!content || !content.length)
		{
			this.contentElm.html("&modules.dashboard.dashboard.No-information-to-display;");
		}
		else
		{
			this.contentElm.html(content);
		}
		return this;
	}


	/**
	 * Toggles the widget's visibility.
	 * @return DashboardWidgetClass this
	 */
	this.toggle = function()
	{
		if (this.collapsed)
		{
			this.contentBlock.slideDown('fast');
		}
		else
		{
			this.contentBlock.slideUp('fast');
		}
		this.collapsed = ! this.collapsed;
		this.buttons.filter('[action="toggle"]').children('img').attr('src', '{UIHOST}/changeicons/small/toggle'+(this.collapsed ? '-expand' : '')+'.png');
		if ( ! this.collapsed && this.refreshOnOpen )
		{
			this.refreshOnOpen = false;
			this.refresh();
		}
		return this;
	}


	/**
	 * Refreshes the content of the widget.
	 * If the widget is collapsed, the refresh process will be called next time
	 * the widget becomes not collapsed.
	 * @return DashboardWidgetClass this
	 */
	this.refresh = function()
	{
		if (this.collapsed)
		{
			this.refreshOnOpen = true;
		}
		else if (!this.element.attr('static'))
		{
			
			var url = (this.refreshURL) ? this.refreshURL : (this.module != null ) ? window.Dashboard.makeUrl(this.module, this.action, {}) : null;
			if (url)
			{
				this.setContent("&modules.dashboard.dashboard.Loading-in-progressEllipsis;");
				var _this = this;
				this.parameters.t = new Date().getTime();
				jQuery.get(url, this.parameters,
					function(data) {_this.onLoad(data);	},
					// Force jQuery to return a simple text string.
					'text');
			}
		}
		return this;
	}

	/**
	 * Open the related module.
	 * @return DashboardWidgetClass this
	 */
	this.gotomodule = function()
	{
		loadModule(this.module);
		return this;
	}
	
	this.trim = function(string)
	{
	    try
	    {
	        var val = String(string);
	        val = val.replace(/^\s+/g, "");
	        val = val.replace(/\s+$/g, "");
	        return val;
	    }
	    catch (e)
	    {
	    }
	}

	this.initialize();
}


// Extend jQuery:
// Add a makeDashboardWidget() method.
jQuery.fn.extend({
	makeDashboardWidget: function()
	{
		return this.each(function() {
			new DashboardWidgetClass(jQuery(this));
			});
	}
});

jQuery(document).ready(function(){
  jQuery('.dashboard-widget:not(.nobuild)').makeDashboardWidget();
});

function widgetToggleElement(imgId, elementId)
{
	var image = jQuery('#' + imgId);
	var element = jQuery('#' + elementId);
	var collapsed = image.attr('collapsed');
	if (collapsed == "true")
	{
		image.attr('collapsed', "false");
		image.attr('src', '{UIHOST}/changeicons/small/toggle_expand.png')
		element.slideDown('fast');
	}
	else
	{
		image.attr('collapsed', "true");
		image.attr('src', '{UIHOST}/changeicons/small/toggle.png')
		element.slideUp('fast');
	}
}
