<div id="ahHeader">
	<div class="ahNewResource">
		<{if $lang_new!=''}><a href="<{$ah_url}>/publish/"><{$lang_new}></a> &bull;<{/if}>
		<a href="<{$ah_url}>/search/&mode=1"><{$lang_voted}></a> &bull;
		<a href="<{$ah_url}>/search/&mode=2"><{$lang_popular}></a> &bull;
		<a href="<{$ah_url}>/search/&mode=3"><{$lang_recent}></a><br />
		<{$location_bar}>
	</div>
	<div class="ahSearch">
		<form name="frmSearch" method="POST" action="<{$ah_url}>/search/">
			<{$lang_findlabel}>
			<input name="search" size="15" type="text" />
			<input name="submit" class="formButton" type="submit" value="<{$lang_find}>"/>
		</form>
	</div>
</div>
