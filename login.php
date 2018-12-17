<?php

  require_once './User.class.php';
  require_once './Helper.class.php';

  if( isset($_POST['login']) ) {
    $u = new User();
    if ( !$u->login( $_POST['email'], $_POST['password'] ) ) {
      Helper::addError('Login failed! Check your email and/or password.');
    }
  }

  if( User::isLoggedIn() ) {
    header('Location: index.php');
  }

?>

<?php include './header.layout.php'; ?>

  <h1 class="mt-5">Login</h1>

  <div class="row justify-content-center">
    <div class="col-md-5">
    
    <form action="./login.php" method="post" class="clearfix mt-5">
      <div class="form-group">
        <label for="inputEmail">Email address</label>
        <input type="email" name="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" placeholder="Enter email">
        <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
      </div>
      <div class="form-group">
        <label for="inputPassword">Password</label>
        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
      </div>
      <button name="login" class="btn btn-primary float-right">
        Log in
      </button>
    </form>

    </div>
  </div>

<?php include './footer.layout.php'; ?>