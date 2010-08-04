function getController()
{
	return parent.document.getElementById('wcontroller');
}

function getModule()
{
	return parent.document.getElementById('wmodule');
}

function getCurrentPageData()
{		    
    return pageData;
}

function setCurrentPageData(name, value)
{		    
    pageData[name] = value;
}

function backToContentEditing()
{
	document.getElementById('mainEditor').backToContentEditing();
}
   		
function onWindowLoaded()
{
	document.getElementById('mainEditor').onWindowLoaded();
}