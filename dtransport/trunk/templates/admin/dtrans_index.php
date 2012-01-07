<div id="dtOptions">
    <{foreach item=info from=$options}>
        <a href="<{$info.link}>" title="<{$info.text}>">
            <img src="<{$info.icon}>" alt="<{$info.text}>" />
            <{$info.text}>
            <span class="dtOptionsInfo"><{$info.info}></span>
        </a>
    <{/foreach}>
</div>

<div id="dtVersion">
	<{if $err_access}>
		<div class="dtErrAccess">
			<{$lang_erraccess}><br /><br />
			<a href="javascript:;" onclick="$('htcode').style.display='block'"><{$lang_showcode}></a>
			<div id="htcode">
				<{$code}>
			</div>
		</div>
	<{/if}>
	<h2><{$lang_recent}></h2>

	<ul id="dtRecents">
	<{foreach item=sec from=$recents}>
		<li><a href="sections.php?op=edit&amp;id=<{$sec.res}>&amp;sec=<{$sec.id}>" class="items"><{$sec.title}></a>
		    <{$sec.text}><br />
		    <span><strong><{$lang_date}></strong> <{$sec.date}> &nbsp;
		    <strong><{$lang_by}></strong>
		    <a href="<{$xoops_url}>/userinfo.php?uid=<{$sec.uid}>"><{$sec.uname}></a></span>
		</li>
	<{/foreach}>
	</ul>

	<h2><{$lang_versions}></h2>
	<div id="versionInfo">
	
	</div>
</div>