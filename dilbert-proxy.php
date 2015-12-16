<?php
$rss_url = 'http://comicfeeds.chrisbenard.net/view/dilbert/default';
$cache_path = dirname(__FILE__) . '/cache';

function cache_exists($cache_path) {
  # Check if the image of the day has already been downloaded
  if (file_exists($cache_path)) {
    $mdate = date('Ymd', filemtime($cache_path));
    $date = date('Ymd');

    if ($mdate === $date) {
      return true;
    }
    else {
      return false;
    }
  }
}

function delete_cache($cache_path) {
  if (file_exists($cache_path)) {
    unlink($cache_path);
  }
}

function create_cache($cache_path, $rss_url) {
  $rss_data = file_get_contents($rss_url);
  $xml = simplexml_load_string($rss_data);
  $json = json_encode($xml);
  $object = json_decode($json);

  $image_tag = $object->entry[0]->content;
  preg_match('/src="([^"]+)"/', $image_tag, $m);
  $image_url = $m[1];

  file_put_contents($cache_path, file_get_contents($image_url));
}

if (!cache_exists($cache_path)) {
  delete_cache($cache_path);
  create_cache($cache_path, $rss_url);
}
header('Content-Type: image/gif');
echo file_get_contents($cache_path);
?>
