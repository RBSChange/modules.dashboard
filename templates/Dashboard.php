<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" tal:attributes="xml:lang lang; lang lang;">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <style tal:replace="cssInclusion" />
        <span tal:replace="scriptInclusion" />
    </head>
    <body style="padding-top: 26px;">

        <!-- HEADER MENU -->
        <table class="header" cellpadding="0" cellspacing="0" style="position: fixed; top: 0px;">
           <tr>

               <!-- HOME ICON -->
               <td class="header-home">
    	           <img change:icon="gauge/command shadow" />
    	       </td>

    	       <!-- USER NAME -->
    	       <td>
    	           <span tal:content="username"/>
    	       </td>

    	       <!-- CURRENT DATE -->
    	       <td class="header-date">
	               <span tal:content="date" />
	           </td>

	           <!-- REFRESH BUTTON -->
               <td class="header-refresh">
                   <img tal:condition="isRoot" change:icon="environment_edit/small shadow" i18n:attributes="title &modules.uixul.bo.actions.Edit-locale;" onclick="editLocale();" style="margin: 0 20px;"/>
                   <img change:icon="refresh/small shadow" i18n:attributes="title &modules.uixul.bo.general.Refresh;" onclick="refresh();" />
               </td>
            </tr>
        </table>
        
        <!-- MAIN CONTENT -->
        <table class="main-content">
            <tr>

               <!-- LEFT COLUMN -->
               <td class="main-content-left">

                   <!-- MODULES QUICK ACCESS -->
    	           <div class="notification-box">
        				<div class="notification-top"></div>
        				<div class="notification-content">
        					<h4 i18n:translate="&modules.uixul.bo.general.Modules;" />

        					<!-- MAIN MODULES -->
        					<ul class="dash-module-big">

        					    <!-- WEBSITE MODULE -->
        						<li><img change:icon="document/normal shadow" /> <a href="javascript:;" onclick="loadModule('website');" i18n:attributes="title &modules.website.bo.general.Module-name;" i18n:translate="&modules.website.bo.general.Module-name;" /></li>

        						<!-- MEDIA MODULE -->
        						<li><img change:icon="cabinet/normal shadow" /> <a href="javascript:;" onclick="loadModule('media');" i18n:attributes="title &modules.media.bo.general.Module-name;" i18n:translate="&modules.media.bo.general.Module-name;" /></li>

        						<!-- NEWS MODULE -->
        						<li><img change:icon="news/normal shadow" /> <a href="javascript:;" onclick="loadModule('news');" i18n:attributes="title &modules.news.bo.general.Module-name;" i18n:translate="&modules.news.bo.general.Module-name;" /></li>

        					</ul>
        					<div class="accordion"><img id="other-modules-more" change:icon="navigate_close/small shadow" onclick="$toggle('other-modules'); return false" value="$toggle" i18n:attributes="title &modules.uixul.bo.dashboard.Show-other-modules; alt &modules.uixul.bo.dashboard.Show-other-modules;" /><img id="other-modules-less" change:icon="navigate_open/small shadow" onclick="$toggle('other-modules'); return false" value="$toggle" i18n:attributes="title &modules.uixul.bo.dashboard.Show-other-modules; alt &modules.uixul.bo.dashboard.Show-other-modules;" style="display: none;" /></div>

        					<!-- OTHER MODULES -->
        					<ul class="dash-module-small" id="other-modules" style="display: none;">

        					    <!-- MAPPING MODULE -->
        						<li><img change:icon="breakpoint_selection/small shadow" /> <a href="javascript:;" onclick="loadModule('mapping');" i18n:attributes="title &modules.mapping.bo.general.Module-name;" i18n:translate="&modules.mapping.bo.general.Module-name;" /></li>

        						<!-- FORM MODULE -->
        						<li><img change:icon="form_blue/small shadow" /> <a href="javascript:;" onclick="loadModule('form');" i18n:attributes="title &modules.form.bo.general.Module-name;" i18n:translate="&modules.form.bo.general.Module-name;" /></li>

        						<!-- LIST MODULE -->
        						<li><img change:icon="cubes_green/small shadow" /> <a href="javascript:;" onclick="loadModule('list');" i18n:attributes="title &modules.list.bo.general.Module-name;" i18n:translate="&modules.list.bo.general.Module-name;" /></li>

        						<!-- USERS MODULE -->
        						<li><img change:icon="users4/small shadow" /> <a href="javascript:;" onclick="loadModule('users');" i18n:attributes="title &modules.users.bo.general.Module-name;" i18n:translate="&modules.users.bo.general.Module-name;" /></li>

        					</ul>
        				</div>
        				<div class="notification-bottom"></div>
        			</div>

        			<!-- OTHER TOOLS -->
        			<div class="notification-box">
        				<div class="notification-top"></div>
        				<div class="notification-content">
        				    <h4 i18n:translate="&modules.uixul.bo.general.Tools;" />
        					<ul class="dash-module-big">

            					<!-- STATS -->
        					    <li><img change:icon="line-chart/normal shadow" /> <a href="javascript:;" onclick="loadStats();" i18n:attributes="title &modules.uixul.bo.general.Stats;" i18n:translate="&modules.uixul.bo.general.Stats;" /></li>

        					</ul>
        				</div>
        				<div class="notification-bottom"></div>
        			</div>
               </td>

               <!-- MIDDLE COLUMN -->
	       <td class="main-content-middle">
                   	<!-- MODULES WIDGETS -->
		   	<div tal:repeat="widget widgets" class="dashboard-widget ${widget}" />
               </td>

               <!-- RIGHT COLUMN -->
               <td class="main-content-right" tal:condition="packageVersion">

                   <!-- PACKAGES -->
    	           <div class="notification-box">
        				<div class="notification-top"></div>
        				<div class="notification-content">
        				    <h4 i18n:translate="&modules.uixul.bo.general.Packages;" />
        				    <ul class="dash-module-small" id="packages">
        						<li tal:repeat="version packageVersion"><span tal:replace="repeat/version/key"/> : <span tal:replace="version" /></li>
        					</ul>
        				</div>
        				<div class="notification-bottom"></div>
        			</div>

               </td>
            </tr>
        </table>

    </body>

</html>
