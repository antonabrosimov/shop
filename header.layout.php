<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>WebShop</title>
  <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="./css/fontawesome-all.min.css" />
  <link rel="stylesheet" type="text/css" href="./css/styles.css" />
</head>
<body>

  <?php include './navbar.inc.php' ?>

  <div class="container mt-4">
    <div class="row">

      <div class="col-md-3">
        <?php include './sidebar.inc.php'; ?>
      </div>

      <div class="col-md-9">
        <div class="content">
        
        <?php require_once './Helper.class.php'; ?>
          
        <?php if(Helper::isMessage()): ?>
          <div class="alert alert-success">
            <b>Success!</b> <?php echo Helper::getMessage(); ?>
          </div>
        <?php endif; ?>

        <?php if(Helper::isError()): ?>
          <div class="alert alert-danger">
            <b>Error!</b> <?php echo Helper::getError(); ?>
          </div>
        <?php endif; ?>