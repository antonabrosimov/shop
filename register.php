<?php

if (isset($_POST['register'])) {
  require_once './User.class.php';
  require_once './Helper.class.php';

  $user = new User();
  $user->name = $_POST['name'];
  $user->email = $_POST['email'];
  $user->password = $_POST['password'];
  $user->password_repeat = $_POST['password_repeat'];
  if ( $user->save() ) {
    Helper::addMessage('Account created successfully. You can now login.');
  }
}

?>

<?php include './header.layout.php'; ?>

<h1 class="mt-5">Register</h1>

<form class="mt-5 clearfix" action="./register.php" method="post">
  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Your name"
        name="name" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        id="inputEmail"
        placeholder="Email"
        name="email" />
    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputPassword">Password</label>
      <input
        type="password"
        class="form-control"
        id="inputPassword"
        placeholder="Choose password"
        name="password" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputPasswordRepeat">Password repeat</label>
      <input
        type="password"
        class="form-control"
        id="inputPasswordRepeat"
        placeholder="Enter password again"
        name="password_repeat" />
    </div>

  </div>

  <button name="register" class="btn btn-primary float-right">
    Create account
  </button>
</form>


<?php include './footer.layout.php'; ?>