<?php

require_once './Product.class.php';

$p = new Product();

if( isset($_GET['cat_id']) ) {
  $products = $p->all($_GET['cat_id']);
} else {
  if ( !isset($_GET['page']) ) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  $config = require './config.inc.php';
  $total_products = $p->numOfProducts();
  $number_of_pages = ceil($total_products / $config['products_per_page']);

  if ($page <= 1) {
    $prev = 1;
  } else {
    $prev = $page - 1;
  }

  if ($page >= $number_of_pages) {
    $next = 1;  // 1 ili $page
  } else {
    $next = $page + 1;
  }

  $products = $p->paginate($page);
}

?>

<?php include './header.layout.php'; ?>

  <h1 class="mt-4 mb-3">Products</h1>

  <div class="row">

    <?php foreach($products as $product): ?>
      <div class="col-md-4">
        <div class="card mt-4">
          <?php if($product->img): ?>
            <img class="card-img-top product-image" src="<?php echo $product->img; ?>" />
          <?php else: ?>
            <img class="card-img-top product-image" src="./img/products/download.svg" />
          <?php endif; ?>
          <div class="card-body clearfix">
            <h4 class="card-title"><?php echo $product->title; ?></h4>
            <p class="card-text">
              <strong>Price:</strong> <?php echo $product->price; ?>RSD
            </p>
            <a href="./product-details.php?id=<?php echo $product->id; ?>" class="card-link">Details</a>
            <a href="#" class="btn btn-sm btn-primary float-right">Add to cart</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>

<?php if( !isset($_GET['cat_id']) ): ?>
  <nav class="mt-5">
    <ul class="pagination justify-content-center">
      <li class="page-item"><a class="page-link" href="./products.php?page=<?php echo $prev; ?>">Previous</a></li>
      <?php for($i = 1; $i <= $number_of_pages; $i++): ?>
        <li class="page-item <?php if($page == $i) { echo 'active'; } ?>">
          <a
            class="page-link"
            href="./products.php?page=<?php echo $i; ?>">
              <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>
      <li class="page-item"><a class="page-link" href="./products.php?page=<?php echo $next; ?>">Next</a></li>
    </ul>
  </nav>
<?php endif; ?>



<?php include './footer.layout.php'; ?>