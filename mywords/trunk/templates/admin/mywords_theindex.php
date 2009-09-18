<div id='mwOptions'>
    <{foreach item=info from=$options}>
		<a href="<{$info.link}>" title="<{$info.text}>" class="items">
			<img src="<{$info.icon}>" alt="<{$info.text}>" />
			<{$info.text}>
			<span><{$info.info}></span>
		</a>
	<{/foreach}>
</div>
<div id="mwVersionPosts">
	<div id="versionInfo">
	
	</div>
</div>