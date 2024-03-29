<?php

$site_meta = $site->meta();
$page_meta = $page->meta();

$locale = $kirby->language()->locale();
$locale = $locale[array_key_first( $locale )];

?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= $page_meta->title() ?></title>

<?php if( option('debug', false) || $page_meta->noindex()->isTrue() ): ?>
    <meta name="robots" content="noindex">
<?php endif ?>

<link rel="canonical" href="<?= $page->url() ?>" />
<meta name="description" content="<?= $page_meta->description() ?>">
<meta name="keywords" content="<?= $page_meta->keywords() ?>">

<meta property="og:title" content="<?= $page_meta->title() ?>">
<meta property="og:description" content="<?= $page_meta->description() ?>">
<meta property="og:url" content="<?= $page->url() ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= $site_meta->title() ?>">
<meta property="og:locale" content="<?= $locale ?>">

<?php if( $image = $page_meta->image()->toFile() ):
    $image = $image->thumb(option('moritzebeling.kirby-meta.preview_image.resize'))->url();
    ?>
    <meta property="og:image" content="<?= $image ?>">
    <meta name="twitter:image" content="<?= $image ?>">
<?php endif ?>

<meta name="format-detection" content="telephone=no">
<meta name="Generator" content="Moritz Ebeling (https://moritzebeling.com)">

<?php foreach ($kirby->languages()->not( $kirby->language() ) as $language): ?>
    <link rel="alternate" hreflang="<?= $language->code() ?>" href="<?= $page->url($language->code()) ?>" />
<?php endforeach ?>

<?= jsonld($site->schema()) ?>
<?= jsonld($page->schema()) ?>