<?php
header('Content-Type: application/xml; charset=utf-8');

$products = json_decode(file_get_contents(__DIR__ . '/products.json'), true);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <url>
    <loc>https://mcshopusa.com/</loc>
    <priority>1.0</priority>
  </url>

  <url>
    <loc>https://mcshopusa.com/#shirts</loc>
  </url>

  <url>
    <loc>https://mcshopusa.com/#hats</loc>
  </url>

<?php foreach ($products as $product): 

  $slug = strtolower($product['name']);
  $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
  $slug = trim($slug, '-');

?>

  <url>
    <loc>
      https://mcshopusa.com/product.php?id=<?= $product['id'] ?>
    </loc>

    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

<?php endforeach; ?>

</urlset>