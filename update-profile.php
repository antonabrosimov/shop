<?php require './user-only.php'; ?>

<?php

require_once './User.class.php';
require_once './Helper.class.php';

$u = new User();
$u->getLoggedInUser();

if ( isset($_POST['update']) ) {
  $u->name = $_POST['name'];
  $u->email = $_POST['email'];
  $u->new_password = $_POST['new_password'];
  $u->password_repeat = $_POST['password_repeat'];
  if ( $u->save() ) {
    Helper::addMessage('Profile updated!');
  }
}

?>

<?php include './header.layout.php'; ?>

<h1 class="mt-5">Update profile</h1>

<form class="mt-5 clearfix" action="./update-profile.php" method="post">
  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Your name"
        value="<?php echo $u->name; ?>"
        name="name" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        id="inputEmail"
        placeholder="Email"
        value="<?php echo $u->email; ?>"
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
        name="new_password" />
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

  <button name="update" class="btn btn-primary float-right">
    Update profile
  </button>
</form>


<?php include './footer.layout.php'; ?>