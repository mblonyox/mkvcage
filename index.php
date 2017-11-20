<?php

require_once './vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Zend\Feed\Reader\Reader;
use Zend\Feed\Writer\Feed;

$input = Reader::import('http://www.mkvcage.com/category/tv-shows/feed');
$output = new Feed;
$output->setTitle('MkvCage torrent feed.');
$output->setDescription('Created by mblonyox.');
$output->setLink('https://mblonyox.com/mkvcage.php');

foreach ($input as $entry) {
  $title = $entry->getTitle();
  $description = $entry->getDescription();
  $crawler = new Crawler($entry->getContent());
  $buttn_torrent = $crawler->filterXPath('//a[@class="buttn torrent"]');
  $buttn_magnet = $crawler->filterXPath('//a[@class="buttn magnet"]');
  if ($buttn_torrent->count() > 0) {
    $link = 'http://www.mkvcage.com/' . $buttn_torrent->attr('href');    
  } elseif ($buttn_magnet->count() > 0) {
    $link = $buttn_magnet->attr('href');
  } else {
    $link = $entry->getLink();
  }

  $item = $output->createEntry();
  $item->setTitle($title);
  $item->setLink($link);
  $item->setDescription($description);
  $output->addEntry($item);  
}

header('Content-Type: application/rss+xml; charset=utf-8');
echo $output->export('rss');
?>