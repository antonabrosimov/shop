<?php

require_once './Product.class.php';
header('Content-Type: application/json');

if ( !isset($_GET['command']) ) {
  $p = new Product($_GET['product_id']);
  $response = [
    'liked' => $p->liked(),
    'likes' => $p->likes()
  ];
  echo json_encode($response);
}

if ( isset($_GET['command']) && $_GET['command'] == 'like' ) {
  $p = new Product($_GET['product_id']);
  $p->like();
  $response = [
    'liked' => $p->liked(),
    'likes' => $p->likes()
  ];
  echo json_encode($response);
}
