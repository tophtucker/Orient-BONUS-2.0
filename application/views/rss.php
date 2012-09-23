<?= '<?xml version="1.0"?>' ?>
<rss version="2.0">
  <channel>
    <title><?= $title ?></title>
    <link><?= $url ?></link>
    <description><?= $description ?></description>
    <? foreach($articles as $article): ?>
    <item>
       <title><?= $article->title ?></title>
       <link>http://bowdoinorient.com/article/<?= $article->id ?></link>
       <pubDate><?= gmdate(DATE_RSS, strtotime($article->date)); ?></pubDate>
       <description><?= $article->pullquote ?></description>
    </item>
    <? endforeach; ?>
  </channel>
</rss>