<?php
include 'includes/config.php';

function create_slug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($slug, '-');
}

$select_products = mysqli_query($conn, "SELECT id, name FROM `products` WHERE slug IS NULL OR slug = ''");
while($row = mysqli_fetch_assoc($select_products)) {
    $slug = create_slug($row['name']) . '-' . $row['id'];
    $pid = $row['id'];
    mysqli_query($conn, "UPDATE `products` SET slug = '$slug' WHERE id = '$pid'");
}

echo "Slugs generated successfully!";
?>
