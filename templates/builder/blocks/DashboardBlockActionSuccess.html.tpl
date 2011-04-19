<div class="dashboard-widget" tal:omit-tag="refresh" refreshURL="${refreshURL}" title="${title}" icon="${icon}" hideGotoModule="${hideGotoModule}">
	<tal:block tal:condition="forEdition">
		<div class="title-bar" ><img src="${icon}" /> ${title}</div>
		<div class="content-block"><div class="content">${transui:m.dashboard.dashboard.dummycontent,ucf}</div></div>
	</tal:block>
	<tal:block tal:condition="not:forEdition">
		<!-- Content here -->
	</tal:block>
</div>