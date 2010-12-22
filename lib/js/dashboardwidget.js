
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
		widget.element.setAttribute('widgetIndex', index.toString());
	};


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
	};


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
		wToolkit.dialog(module, action, parameters, windowParams, true);
		window.Dashboard.refresh();
	};

	this.closeDetail = function()
	{
		window.close();
	};
	
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
	};
	
	this.openTaskWithParameters = function(taskId, dialog, moduleName, parameters)
	{
		var wController = parent.window.document.getElementById('wcontroller');
		var module = wController.getModuleByName(moduleName);
		if (module && module.getAttribute('version') == 'v2')
		{
			parameters.cmpref = taskId;
			this.openDetail('task', 'ViewUserTask', parameters, true);
		}
		else
		{
			parameters.taskId = taskId;
			wController.openModalDialog(this, dialog, parameters);
		}
	};

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
	};
	
	this.encodeParameters = function(parameters)
	{
		if (parameters == null) {return '';}
		var encParams = [];
		for (var name in parameters) 
		{
			var value = parameters[name];
			if (value != null)
			{
				if (typeof(value) == 'object' && 'length' in value) 
				{
					for (var i=0; i<value.length; i++) {
						encParams.push(name + '[]=' + encodeURIComponent(value[i]));
					}
				} 
				else if (typeof(value) == 'object') 
				{
					for (var key in value)
					{
						encParams.push(name + '['+encodeURIComponent(key)+ ']=' + encodeURIComponent(value[key]));
					}
				}
				else if (typeof(value) != 'function') 
				{
					encParams.push(name + '=' + encodeURIComponent(value));
				}
		    }
		}
		return encParams.join('&');
	};

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
	};
	
	this.getWidgetByNode = function(element)
	{
		while (element != null && !element.hasAttribute('widgetIndex')) 
		{
			element = element.parentNode;
		}
		if (element == null) {return null;}
		var index = parseInt(element.getAttribute('widgetIndex'));
		return this.widgets[index];
	};

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
		if (!this.element.hasAttribute('static'))
		{
			this.element.innerHTML = 
				  '<div class="title-bar">'
				+   '<div class="icon"></div>'
				+   '<div class="title"></div>'
				+   '<div class="buttons-bar">'
				+     '<a class="button" action="gotomodule" href="javascript:;" title="${transui:m.dashboard.dashboard.gotomodule,ucf,js,etc}"><img src="{UIHOST}/changeicons/small/search.png"/></a>'
				+     '<a class="button" action="refresh" href="javascript:;" title="${transui:m.dashboard.dashboard.refresh,ucf,js}"><img src="{UIHOST}/changeicons/small/refresh.png"/></a>'
				+     '<a class="button" action="toggle" href="javascript:;" title="${transui:m.dashboard.dashboard.toggle,ucf,js}"><img src="{UIHOST}/changeicons/small/toggle.png"/></a>'
				+   '</div>'
				+ '</div>'
				+ '<div class="content-block">'
				+   '<div class="content">'+this.element.innerHTML+'</div>'
				+ '</div>';
		}

		var parameters = this.element.getAttribute('parameters');
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

		this.contentBlock = this.element.querySelector('.content-block');
		this.contentElm   = this.contentBlock.querySelector('.content');
		this.titleElm     = this.element.querySelector('.title-bar .title');
		this.iconElm      = this.element.querySelector('.title-bar .icon');
		this.buttons      = this.element.querySelectorAll('.title-bar .buttons-bar .button');

		var _this = this;

		this.element.querySelector('.title-bar').addEventListener('dblclick', function (event) {_this.toggle();}, true);
		
		window.Dashboard.registerWidget(this);

		if (this.element.getAttribute('title'))
		{
			this.setTitle(this.element.getAttribute('title'), this.element.getAttribute('icon'));
		}

		if (this.element.hasAttribute('refreshURL'))
		{
			this.refreshURL = this.element.getAttribute('refreshURL');
			this.module = this.refreshURL.match(/dashboardParam%5Btype%5D=([^_]*)_/)[1];
			if (this.element.hasAttribute('refreshInterval'))
			{
				var interval = parseInt(this.element.getAttribute('refreshInterval')) * 1000;
				window.setInterval(this.refreshInterval, interval, _this);
			}
			if (this.element.hasAttribute('refreshOnLoad'))
			{
				window.setTimeout(this.refreshInterval, 100, _this);
			}
		}
		else
		{
			// Finds modules name from the class attribute
			var classArray = this.element.getAttribute('class').split(/\s+/);
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
		var hideGotoModule = this.element.getAttribute('hideGotoModule') == 'true';
		for (var i = 0; i < this.buttons.length; i++)
		{
			this.addActionClick(this.buttons[i], hideGotoModule);			
		}
	}
	
	this.refreshInterval = function(me)
	{
		if (!me.collapsed)
		{
			me.refresh();
		}
	}
	
	this.addActionClick = function(btn, hideGotoModule)
	{
		var href = btn.getAttribute('action');
		if (href == 'gotomodule' && hideGotoModule)
		{
			btn.style.display = 'none';
		}
		else
		{
			var me = this;
			btn.addEventListener('click', function (event) {me[href]();}, true);
		}		
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
				"${transui:m.dashboard.dashboard.loading-error,ucf,js}" + " (" + this.module + "/" + this.action + ")",
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
		this.titleElm.innerHTML = title;
		if (icon && icon.length)
		{
			this.iconElm.setAttribute('style', 'background:url('+icon+') no-repeat 0 0;width: 18px;height: 16px;');
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
			this.contentElm.innerHTML = "${transui:m.dashboard.dashboard.no-information-to-display,ucf,js}";
		}
		else
		{
			this.contentElm.innerHTML = content;
		}
		return this;
	}


	/**
	 * Toggles the widget's visibility.
	 * @return DashboardWidgetClass this
	 */
	this.toggle = function()
	{
		try 
		{
			if (this.collapsed)
			{
				this.contentBlock.style.display = 'block';
			}
			else
			{
				this.contentBlock.style.display = 'none';
			}
			this.collapsed = ! this.collapsed;
			var img = this.element.querySelector('[action="toggle"] img');
			img.setAttribute('src', '{UIHOST}/changeicons/small/toggle'+(this.collapsed ? '-expand' : '')+'.png');
			
			if ( ! this.collapsed && this.refreshOnOpen )
			{
				this.refreshOnOpen = false;
				this.refresh();
			}
		}
		catch (e)
		{
			alert(e);
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
		else if (!this.element.hasAttribute('static'))
		{
			var url = (this.refreshURL) ? this.refreshURL : (this.module != null ) ? window.Dashboard.makeUrl(this.module, this.action, {}) : null;
			if (url)
			{
				this.setContent("${transui:m.dashboard.dashboard.loading-in-progress,ucf,etc,js}");		
				this.parameters.t = new Date().getTime();
				var postData = window.Dashboard.encodeParameters(this.parameters);
				
				var req = new XMLHttpRequest();
				req.open('POST', url, true);
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				req.setRequestHeader('Content-Length', postData.length);
				
				var me = this;
				req.onreadystatechange = function (aEvt) {  
					if (req.readyState == 4) {me.onLoad(req.responseText);}  
				};  
				req.send(postData);
			}
		}
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
	    return string;
	}

	this.initialize();
}

addEventListener('load', function () {
var nodes = document.querySelectorAll('.dashboard-widget:not(.nobuild)');
for (var i = 0; i < nodes.length; i++)
{
	
	new DashboardWidgetClass(nodes[i]);
}
}, true);

function widgetToggleElement(imgId, elementId)
{
	var image = document.getElementById(imgId);
	var element = document.getElementById(elementId);
	var collapsed = image.getAttribute('collapsed');
	if (collapsed == "true")
	{
		image.setAttribute('collapsed', "false");
		image.setAttribute('src', '{UIHOST}/changeicons/small/toggle_expand.png')
		element.style.display = 'none';
	}
	else
	{
		image.setAttribute('collapsed', "true");
		image.setAttribute('src', '{UIHOST}/changeicons/small/toggle.png')
		element.style.display = 'block';
	}
}
