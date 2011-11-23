function editLocale()
{
	if (parent.window && parent.window.editLocale)
	{
		parent.window.editLocale();
	}
}

function editLocaleForModule(moduleName)
{
    if (parent.window && parent.window.editLocaleForModule)
    {
        parent.window.editLocaleForModule(moduleName);
    }
}

function openPreferences()
{
    if (parent.window)
    {
        var mainMenu = parent.window.document.getElementById('change4-menubox');
        if (mainMenu)
        {
        	mainMenu.openPreferences();
        }
    }
}

function openActionUri(uri)
{
    if (uri && parent.window && parent.window.openActionUri)
    {
        parent.window.openActionUri(uri);
    }
}


function loadModule(moduleName, perspectiveName, locateDocumentId, onLoadAction)
{
    if (moduleName && parent.window && parent.window.loadModule)
    {
        parent.window.loadModule(moduleName, perspectiveName, locateDocumentId, onLoadAction);
    }
}



function locateDocumentInModule(documentId, moduleName)
{
    if (moduleName && parent.window && parent.window.locateDocumentInModule)
    {
        parent.window.locateDocumentInModule(documentId, moduleName);
    }
}

function openWebConsole()
{
	var menu = parent.window.document.getElementById('change4-menubox');
	menu.openWebConsole();
}

function performActionOnDocumentInModule(actionName, documentId, moduleName)
{
    if (moduleName && parent.window && parent.window.performActionOnDocumentInModule)
    {
        parent.window.performActionOnDocumentInModule(actionName, documentId, moduleName);
    }
}

function performActionOnModule(actionName, moduleName)
{
    if (moduleName && parent.window && parent.window.performActionOnModule)
    {
        parent.window.performActionOnModule(actionName, moduleName);
    }
}

function openDialog(moduleName, actionName, reqParam, winParam)
{
    if (moduleName && actionName && parent.window && parent.window.wToolkit)
    {
        parent.window.wToolkit.dialog(moduleName, actionName, reqParam, winParam);
    }
}

function loadStats(host)
{
    if (!host)
    {
        host = Context.UIBASEURL;
    }

    var url = host + '/cgi-bin/awstats.pl';

    if (parent.window && parent.window.checkUrl && parent.window.checkUrl(url))
    {
        document.location.replace(url);
    }
    else
    {
        alert("&modules.uixul.bo.dashboard.Stats-not-available;");
    }
}

function refresh()
{
    window.Dashboard.refresh();
}

function renderBenchTimes(benchTimes) {return;}

