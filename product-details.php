<?php require_once './Helper.class.php'; ?>
<?php require_once './Product.class.php'; ?>
<?php require_once './User.class.php'; ?>

<?php

  $u = new User();
  $u->getLoggedInUser();

  if ( isset($_GET['id']) ) {
    
    $p = new Product($_GET['id']);

    if ( isset($_POST['add_comment']) ) {
      $p->addComment($_POST['comment_body']);
    }

    if ( isset($_POST['delete_comment']) ) {
      $p->deleteComment($_POST['comment_id']);
    }

    if ( isset($_POST['add_to_cart']) && User::isLoggedIn() ) {
      if ( $p->addToCart($_POST['quantity']) ) {
        Helper::addMessage($p->title . ' has been added to your shopping cart.');
      } else {
        Helper::addError('Failed to add ' . $p->title . ' to your shopping cart.');
      }
    }

    $comments = $p->comments();
  } else {
    header('Location: products.php');
    die();
  }

?>

<?php include './header.layout.php'; ?>

<!-- Likes: Prebacuje se product ID iz PHPa u JavaScript -->
<script type="text/javascript">
  var product_id = '<?php echo $p->id; ?>';
</script>

  <h1><?php echo $p->title; ?></h1>

  <div class="row mt-5">

    <div class="col-md-4">
      <img src="<?php echo $p->img; ?>" class="img-fluid" />
    </div>
    <div class="col-md-8 clearfix">
      <h3>Description</h3>
      <p>
        <?php echo $p->description; ?>
      </p>
      <h4 class="float-right mt-3"><?php echo $p->price; ?>.00 RSD</h4>
      <br clear="all" />
      <form action="" method="post" class="form-inline float-right">
        <div class="input-group">
          <input type="number" name="quantity" value="1" class="form-control" placeholder="Quantity" min="1">
          <div class="input-group-append">
            <button name="add_to_cart" class="btn btn-outline-success" <?php if (!User::isLoggedIn()) { echo 'data-toggle="popover" data-trigger="hover" data-content="You need to be logged in to add product to cart."'; }  ?> >
              Add to cart
            </button>
          </div>
        </div>
      </form>
    </div>



  </div>


<div class="row">
  <div class="col-md-12">
    <div class="likes">
      <span class="like-icon"></span> <span class="like-text"></span> (<span class="num-of-likes">loading...</span>)
    </div>
  </div>
</div>

  <h2 class="mt-4 mb-4">Comments</h2>

  <div class="row">
    <div class="col-md-12">

      <?php if(User::isLoggedIn()): ?>
        <form action="" method="post" class="clearfix">
          <div class="form-group">
            <label for="inputCommentBody">Add comment</label>
            <textarea class="form-control" name="comment_body" id="inputCommentBody" rows="3"></textarea>
          </div>
          <button name="add_comment" class="btn btn-primary float-right">
            <i class="fas fa-comments"></i>
            Add comment
          </button>
        </form>
      <?php endif; ?>

      <?php if(!User::isLoggedIn() || empty($comments)): ?>
        <div class="alert alert-info mt-4">
          <b>Info!</b>
          <?php if ( empty($comments) ): ?>
            There are no comments on this product at the moment.
          <?php endif; ?>
          <?php if(!User::isLoggedIn()): ?>
            <a href="./login.php">Log in</a> to add comment.
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php foreach($comments as $comment): ?>
      <div class="card mt-3">
        <div class="card-body clearfix">
          <p class="card-text">
            <?php echo $comment->body; ?>
          </p>
          <h6 class="card-subtitle text-muted float-right">
            Posted by
            <i class="fas fa-user"></i>
            <?php echo $comment->name; ?>
            on
            <i class="far fa-calendar-alt"></i>
            <?php echo $comment->created_at; ?>
          </h6>
        </div>

        <?php if($u->acc_type == 'admin'): ?>
          <form action="" method="post" class="delete-comment-form">
            <input type="hidden" name="comment_id" value="<?php echo $comment->id; ?>" />
            <button name="delete_comment" class="btn btn-outline-danger mt-2 mr-2">
              <i class="far fa-trash-alt"></i>
            </button>
          </form>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      

    </div>
  </div>

<?php include './footer.layout.php'; ?>