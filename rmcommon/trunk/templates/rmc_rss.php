<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
  <channel>
    <title><?php echo $rss_channel['title']; ?></title>
    <link><?php echo $rss_channel['link']; ?></link>
    <description><?php echo $rss_channel['description']; ?></description>
    <lastBuildDate><?php echo $rss_channel['lastbuild']; ?></lastBuildDate>
    <docs>http://backend.userland.com/rss/</docs>
    <generator><?php echo $rss_channel['generator']; ?></generator>
    <category><?php echo $rss_channel['category']; ?></category>
    <managingEditor><?php echo $rss_channel['editor']; ?></managingEditor>
    <webMaster><?php echo $rss_channel['webmaster']; ?></webMaster>
    <language><?php echo $rss_channel['language ']; ?></language>
    <?php if($rss_channel['image']['url'] != ""): ?>
    <image>
      <title><?php echo $rss_channel['title']; ?></title>
      <url><?php echo $rss_channel['image']['url']; ?></url>
      <link><?php echo $rss_channel['link']; ?></link>
      <width><?php echo $rss_channel['image']['width']; ?></width>
      <height><?php echo $rss_channel['image']['height']; ?></height>
    </image>
    <?php endif; ?>
    <{foreach item=item from=$items}>
    <item>
      <title><{$item.title}></title>
      <link><{$item.link}></link>
      <description><{$item.description}></description>
      <pubDate><{$item.pubdate}></pubDate>
      <guid><{$item.guid}></guid>
    </item>
    <{/foreach}>
  </channel>
</rss>