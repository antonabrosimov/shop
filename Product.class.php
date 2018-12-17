<?php

class Product {
  private $db;
  public $id;
  public $cat_id;
  public $title;
  public $description;
  public $img;
  public $imageData;
  public $price;
  public $created_at;
  public $updated_at;
  public $deleted_at;
  public $productImgPath = './img/products/';

  function __construct($id = null) {
    $this->db = require './db.inc.php';

    if ($id) {
      $this->id = $id;
      $this->loadFromDatabase();
    }
  }

  public function loadFromDatabase() {
    $stmt_getProduct = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `id` = :id
    ");
    $stmt_getProduct->execute([
      ':id' => $this->id
    ]);
    $product = $stmt_getProduct->fetch();
    if ($product) {
      $this->id = $product->id;
      $this->cat_id = $product->cat_id;
      $this->title = $product->title;
      $this->description = $product->description;
      $this->img = $product->img;
      $this->price = $product->price;
      $this->created_at = $product->created_at;
      $this->updated_at = $product->updated_at;
      $this->deleted_at = $product->deleted_at;
      return true;
    } else {
      return false;
    }
  }

  public function save() {
    if ($this->id) {
      return $this->update();
    } else {
      return $this->insert();
    }
  }

  public function insert() {
    $this->makeImageDirectory();

    $stmt_insertProduct = $this->db->prepare("
      INSERT INTO `products`
      (`cat_id`, `title`, `description`, `img`, `price`)
      VALUES
      (:cat_id, :title, :description, :img, :price)
    ");
    $result = $stmt_insertProduct->execute([
      ':cat_id' => $this->cat_id,
      ':title' => $this->title,
      ':description' => $this->description,
      ':img' => $this->img,
      ':price' => $this->price
    ]);
    if ($result) {
      $this->id = $this->db->lastInsertId();
      $this->handleImage();
      $this->loadFromDatabase();
      return $result;
    } else {
      return false;
    }
  }

  public function handleImage() {
    // ako je doslo do greske prilikom uploada obustavi sve
    if ($this->imageData['error'] != 0) {
      return false;
    }

    // citanje ekstenzije
    $ext = pathinfo($this->imageData['name'], PATHINFO_EXTENSION);
    // kreiranje novog imena za fajl
    $filename = $this->id . '.' . $ext;
    // premestanje slike iz tmp foldera u ./img/products/

    // ToDo: provere slike

    move_uploaded_file(
      $this->imageData['tmp_name'],
      $this->productImgPath . $filename
    );
    // cuvanje putanje do slike u bazi
    $this->img = $this->productImgPath . $filename;
    $this->save();
  }

  public function update() {
    $stmt_updateProduct = $this->db->prepare("
      UPDATE `products`
      SET
        `cat_id` = :cat_id,
        `title` = :title,
        `description` = :description,
        `img` = :img,
        `price` = :price
      WHERE `id` = :id
    ");
    return $stmt_updateProduct->execute([
      ':cat_id' => $this->cat_id,
      ':title' => $this->title,
      ':description' => $this->description,
      ':img' => $this->img,
      ':price' => $this->price,
      ':id' => $this->id
    ]);
  }

  public function delete() {
    $stmt_deleteProduct = $this->db->prepare("
      UPDATE `products`
      SET `deleted_at` = NOW()
      WHERE `id` = :id
    ");
    return $stmt_deleteProduct->execute([
      ':id' => $this->id
    ]);
  }

  public function all($catId = null) {
    if($catId) {
      $stmt_getAllProducts = $this->db->prepare("
        SELECT *
        FROM `products`
        WHERE `deleted_at` IS NULL
        AND `cat_id` = :cat_id
        ORDER BY `created_at` DESC
      ");
      $stmt_getAllProducts->execute([
        ':cat_id' => $catId
      ]);
    } else {
      $stmt_getAllProducts = $this->db->prepare("
        SELECT *
        FROM `products`
        WHERE `deleted_at` IS NULL
        ORDER BY `created_at` DESC
      ");
      $stmt_getAllProducts->execute();
    }
    return $stmt_getAllProducts->fetchAll();
  }

  public function paginate($page = 1) {
    $config = require './config.inc.php';
    $offset = ($page - 1) * $config['products_per_page'];
    $stmt_getProducts = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `deleted_at` IS NULL
      ORDER BY `id` DESC
      LIMIT {$config['products_per_page']}
      OFFSET $offset
    ");
    $stmt_getProducts->execute();
    return $stmt_getProducts->fetchAll();
  }

  public function numOfProducts() {
    $stmt_numOfProducts = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `deleted_at` IS NULL
    ");
    $stmt_numOfProducts->execute();
    return $stmt_numOfProducts->rowCount();
  }

  public function addComment($body) {
    require_once './Helper.class.php';
    Helper::sessionStart();

    $stmt_addComment = $this->db->prepare("
      INSERT INTO `comments`
      (`user_id`, `product_id`, `body`)
      VALUES
      (:user_id, :product_id, :body)
    ");
    $stmt_addComment->execute([
      ':user_id' => $_SESSION['user_id'],
      ':product_id' => $this->id,
      ':body' => htmlentities($body)
    ]);
  }

  public function comments() {
    $stmt_getComments = $this->db->prepare("
      SELECT
        `comments`.`id`,
        `users`.`name`,
        `comments`.`body`,
        `comments`.`created_at`
      FROM `comments`, `users`
      WHERE `comments`.`product_id` = :product_id
      AND `comments`.`deleted_at` IS NULL
      AND `comments`.`user_id` = `users`.`id`
      ORDER BY `comments`.`created_at` DESC
    ");
    $stmt_getComments->execute([
      ':product_id' => $this->id
    ]);
    return $stmt_getComments->fetchAll();
  }

  public function deleteComment($id) {
    $stmt_deleteComment = $this->db->prepare("
      UPDATE `comments`
      SET `deleted_at` = NOW()
      WHERE `id` = :id
    ");
    return $stmt_deleteComment->execute([ ':id' => $id ]);
  }

  public function addToCart($quantity = 1) {
    require_once './Helper.class.php';
    Helper::sessionStart();

    $stmt_getProductFromCart = $this->db->prepare("
      SELECT *
      FROM `carts`
      WHERE `user_id` = :user_id
      AND `product_id` = :product_id
    ");
    $stmt_getProductFromCart->execute([
      ':user_id' => $_SESSION['user_id'],
      ':product_id' => $this->id
    ]);
    $productInCart = $stmt_getProductFromCart->fetch();

    if($productInCart) {
      $stmt_updateProductInCart = $this->db->prepare("
        UPDATE `carts`
        SET `quantity` = :quantity
        WHERE `user_id` = :user_id
        AND `product_id` = :product_id
      ");
      return $stmt_updateProductInCart->execute([
        ':quantity' => $productInCart->quantity + $quantity,
        ':user_id' => $_SESSION['user_id'],
        ':product_id' => $this->id
      ]);
    } else {
      $stmt_addToCart = $this->db->prepare("
        INSERT INTO `carts`
        (`user_id`, `product_id`, `quantity`)
        VALUES
        (:user_id, :product_id, :quantity)
      ");
      return $stmt_addToCart->execute([
        ':user_id' => $_SESSION['user_id'],
        ':product_id' => $this->id,
        ':quantity' => $quantity
      ]);
    }
  }

  public function makeImageDirectory() {
    if ( !file_exists($this->productImgPath) ) {
      mkdir($this->productImgPath, 0777, true);
    }
  }

  public function like() {
    require_once './Helper.class.php';
    Helper::sessionStart();

    if ( $this->liked() ) {
      // dislike
      $stmt_dislike = $this->db->prepare("
        DELETE
        FROM `likes`
        WHERE `user_id` = :user_id
        AND `product_id` = :product_id
      ");
      $stmt_dislike->execute([
        ':product_id' => $this->id,
        ':user_id' => $_SESSION['user_id']
      ]);
    } else {
      // like
      $stmt_like = $this->db->prepare("
        INSERT INTO `likes`
        (`product_id`, `user_id`)
        VAlUES
        (:product_id, :user_id)
      ");
      $stmt_like->execute([
        ':product_id' => $this->id,
        ':user_id' => $_SESSION['user_id']
      ]);
    }
  }

  public function liked() {
    require_once './Helper.class.php';
    Helper::sessionStart();

    $stmt_liked = $this->db->prepare("
      SELECT *
      FROM `likes`
      WHERE `product_id` = :product_id
      AND `user_id` = :user_id
    ");
    $stmt_liked->execute([
      ':product_id' => $this->id,
      ':user_id' => $_SESSION['user_id']
    ]);
    $like = $stmt_liked->fetch();
    if ($like) {
      return true;
    } else {
      return false;
    }
  }

  public function likes() {
    $stmt_likes = $this->db->prepare("
      SELECT *
      FROM `likes`
      WHERE `product_id` = :product_id
    ");
    $stmt_likes->execute([
      ':product_id' => $this->id
    ]);
    return $stmt_likes->rowCount();
  }
}