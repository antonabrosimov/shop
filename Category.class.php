<?php

class Category {
  private $db;
  public $id;
  public $title;
  public $description;

  function __construct($id = null) {
    $this->db = require "./db.inc.php";

    // ako postoji ID preuzimi podatke iz baze
    if ($id) {
      $stmt_getCategory = $this->db->prepare("
        SELECT *
        FROM `categories`
        WHERE `id` = :id
      ");
      $stmt_getCategory->execute([ ':id' => $id ]);
      $category = $stmt_getCategory->fetch();

      $this->id = $category->id;
      $this->title = $category->title;
      $this->description = $category->description;
    }
  }

  public function insert() {

    if (!$this->validate()) {
      return false;
    }

    $stmt_insertCategory = $this->db->prepare("
      INSERT INTO `categories`
      (`title`, `description`)
      VALUES
      (:title, :description)
    ");
    $stmt_insertCategory->execute([
      ":title" => $this->title,
      ":description" => $this->description
    ]);
    $this->id = $this->db->lastInsertId();
  }

  public function all() {
    $stmt_getAllCategories = $this->db->prepare("
      SELECT id, title, description, (
        SELECT count(*)
        FROM `products`
        WHERE `cat_id` = categories.id
        AND `deleted_at` IS NULL
      ) as num_of_products
      FROM `categories`
      ORDER BY `title` ASC
    ");
    $stmt_getAllCategories->execute();
    return $stmt_getAllCategories->fetchAll();
  }

  public function update() {
    $stmt_updateCategory = $this->db->prepare("
      UPDATE `categories`
      SET
        `title` = :title,
        `description` = :description
      WHERE `id` = :id
    ");
    $stmt_updateCategory->execute([
      ':id' => $this->id,
      ':title' => $this->title,
      ':description' => $this->description
    ]);
  }

  public function save() {
    if ($this->id) {
      $this->update();
    } else {
      $this->insert();
    }
  }

  public function delete() {
    $stmt_deleteCategory = $this->db->prepare("
      DELETE FROM `categories`
      WHERE `id` = :id
    ");
    $stmt_deleteCategory->execute([
      ':id' => $this->id
    ]);
  }

  public function validate() {
    if ($this->title == "") {
      return false;
    }

    if (!$this->title) {
      return false;
    }

    return true;
  }
}
