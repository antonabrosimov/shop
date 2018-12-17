<?php require './admin-only.php'; ?>

<?php

// var_dump($_FILES);

  require_once './Category.class.php';
  require_once './Product.class.php';
  require_once './Helper.class.php';

  $c = new Category();
  $categories = $c->all();
  
  if ( isset($_POST['add']) ) {
    $p = new Product();
    $p->title = $_POST['title'];
    $p->cat_id = $_POST['cat_id'];
    $p->price = $_POST['price'];
    $p->description = $_POST['description'];
    $p->imageData = $_FILES['image'];
    if( $p->save() ) {
      Helper::addMessage('Product added successfully!');
    } else {
      Helper::addError('Failed to add product!');
    }
  }

?>

<?php include './header.layout.php'; ?>

<h1 class="mt-5 mb-5">Add product</h1>

<form action="./add-product.php" method="post" class="clearfix" enctype="multipart/form-data">

  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputTitle">Title</label>
      <input type="text" name="title" class="form-control" id="inputTitle" placeholder="Product title">
    </div>

    <div class="form-group col-md-6">
      <label for="inputCategory">Category</label>
      <select name="cat_id" id="inputCategory" class="form-control">
        <?php foreach($categories as $category): ?>
          <option value="<?php echo $category->id; ?>">
            <?php echo $category->title; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputImage">Image</label>
      <input type="file" name="image" id="inputImage" class="form-control-file" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputPrice">Price</label>
      <input type="number" name="price" id="inputPrice" class="form-control" />
    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-12">
      <label for="inputDescription">Description</label>
      <textarea name="description" class="form-control" id="inputDescription" rows="3"></textarea>
    </div>

  </div>

  <button name="add" class="btn btn-primary float-right">Add product</button>

</form>

<?php include './footer.layout.php'; ?>