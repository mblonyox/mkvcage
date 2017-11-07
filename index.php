<?php

require_once './vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Zend\Feed\Reader\Reader;
use Zend\Feed\Writer\Feed;

$input = Reader::import('http://www.mkvcage.com/feed');
$output = new Feed;
$output->setTitle('MkvCage torrent feed.');
$output->setDescription('Created by mblonyox.');
$output->setLink('https://mblonyox.com/mkvcage.php');

foreach ($input as $entry) {
  $title = $entry->getTitle();
  $description = $entry->getDescription();
  $crawler = new Crawler($entry->getContent());
  $link = 'http://www.mkvcage.com/' . $crawler->filterXPath('//a[@class="buttn torrent"]')->attr('href');

  $item = $output->createEntry();
  $item->setTitle($title);
  $item->setLink($link);
  $item->setDescription($description);
  $output->addEntry($item);  
}

header('Content-Type: application/rss+xml; charset=utf-8');
echo $output->export('rss');
?>