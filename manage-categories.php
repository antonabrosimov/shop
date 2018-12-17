<?php require './admin-only.php'; ?>

<?php

require_once './Category.class.php';

if ( isset($_POST['delete']) ) {
  $catToBeDeleted = new Category($_POST['cat_id']);
  $catToBeDeleted->delete();
}

if ( isset($_POST['add']) ) {
  $catToAdd = new Category();
  $catToAdd->title = $_POST['title'];
  $catToAdd->description = $_POST['description'];
  $catToAdd->save();
}

$c = new Category();
$categories = $c->all();

?>

<?php include './header.layout.php'; ?>

  <h1 class="mt-5">Manage categories</h1>

  <button class="btn btn-outline-success add-category-button">
    Add Category
  </button>

  <form method="post" class="add-category-form clearfix">

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" class="form-control" name="title" id="title"
        placeholder="Enter category title here..." />
    </div>

    <div class="form-group">
      <label for="description">Example textarea</label>
      <textarea class="form-control" id="description" rows="3"
      name="description" placeholder="Enter description here..."></textarea>
    </div>

    <button class="btn btn-outline-primary float-right" name="add">
      Add category
    </button>

  </form>

  <h1>All categories</h1>

  <table class="table table-hover mt-5">
    <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>

<?php foreach($categories as $category): ?>
      <tr>
        <th><?php echo $category->id; ?></th>
        <td><?php echo $category->title; ?></td>
        <td><?php echo $category->description; ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="cat_id"
              value="<?php echo $category->id; ?>" />
            <button class="btn btn-sm btn-outline-danger" name="delete">
              Delete
            </button>
          </form>
        </td>
      </tr>
<?php endforeach; ?>

    </tbody>
  </table>

<?php include './footer.layout.php'; ?>
