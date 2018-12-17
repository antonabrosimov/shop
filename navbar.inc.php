<?php require_once './User.class.php'; ?>

<?php
  $user = new User();
  $user->getLoggedInUser();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
      <i class="fas fa-shopping-bag"></i>
        WebShop
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="./">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./products.php">Products</a>
          </li>

        </ul>

        <ul class="navbar-nav">
          <?php if (User::isLoggedIn()): ?>
            <?php $productsInCart = $user->getCart(); ?>
            <li class="nav-item">
              <a href="./cart.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                Cart
                <sup><span class="badge badge-light">
                  <?php echo count($productsInCart); ?>
                </span></sup>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                <?php echo $user->name; ?> (<?php echo $user->email; ?> )
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="./update-profile.php">Update profile</a>

                <?php if($user->acc_type == 'admin'): ?>
                  <h6 class="dropdown-header">Administration</h6>
                  <a class="dropdown-item" href="./add-product.php">Add product</a>
                  <a class="dropdown-item" href="./manage-categories.php">Manage categories</a>
                <?php endif; ?>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="./logout.php">Log out</a>
              </div>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="./login.php" class="nav-link">Login</a>
            </li>
            <li class="nav-item">
              <a href="./register.php" class="nav-link">Sign Up</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>