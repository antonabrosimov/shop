<?php require_once './User.class.php'; ?>
<?php require_once './Helper.class.php'; ?>

<?php
  $u = new User();
  
  if( isset($_POST['remove_from_cart']) ) {
    if ( $u->removeFromCart($_POST['cart_id']) ) {
      Helper::addMessage('Product removed from cart.');
    } else {
      Helper::addError('Failed to remove product from cart.');
    }
  }
  
  if ( isset( $_POST['update_quantity'] ) ) {
    if( $u->updateCart($_POST['cart_id'], $_POST['quantity']) ) {
      Helper::addMessage('Your cart has been updated successfully.');
    } else {
      Helper::addError('Failed to update cart.');
    }
  }

  $products = $u->getCart();
?>

<?php include './header.layout.php'; ?>

  <h1>Cart</h1>

  <table class="table table-hover mt-5">
    <thead class="thead-light">
      <tr>
        <th>Title</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>

    <?php $total_price = 0; ?>
    <?php foreach($products as $product): ?>
    <?php $total_price += $product->price * $product->quantity; ?>
      <tr>
        <th><?php echo $product->title; ?></th>
        <td>
          <form action="" method="post" class="form-inline float-right">
            <div class="input-group">
              <input type="number" name="quantity" value="<?php echo $product->quantity; ?>" class="form-control" placeholder="Quantity" min="1" />
              <input type="hidden" name="cart_id" value="<?php echo $product->id; ?>" class="form-control" />
              <div class="input-group-append">
                <button name="update_quantity" class="btn btn-outline-success">
                  Update
                </button>
              </div>
            </div>
          </form>
        </td>
        <td><?php echo $product->price; ?>.00RSD</td>
        <td><?php echo $product->quantity * $product->price; ?>.00RSD</td>
        <td>
          <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $product->id; ?>">
            <button name="remove_from_cart" class="btn btn-sm btn-danger">Remove from cart</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th></th>
        <th></th>
        <th>Total price:</th>
        <th><?php echo $total_price; ?>.00 RSD</th>
        <th></th>
      </tr>
    </tfoot>
  </table>

<?php include './footer.layout.php'; ?>