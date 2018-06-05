<?php
include 'inc/connect.php';
session_start();

if (isset($_POST['add_to_cart'])) {
  if(isset($_SESSION['shopping_cart'])){
    $item_array_id = array_column($_SESSION['shopping_cart'], 'item_id');
    if (!in_array($_GET['id'], $item_array_id)) {
      $count = count($_SESSION['shopping_cart']);
      $item_array = array(
        'item_id' => $_GET['id'],
        'item_name' => $_POST['hidden_name'],
        'item_price' => $_POST['hidden_price'],
        'item_quantity' => $_POST['quantity']
      );
      $_SESSION['shopping_cart'][$count] = $item_array;
    }else {
      echo '<script>alert("Item Already Added")</script>';
      echo '<script>window.location="index.php"</script>';
    }
  }else {
    $item_array = array(
      'item_id' => $_GET['id'],
      'item_name' => $_POST['hidden_name'],
      'item_price' => $_POST['hidden_price'],
      'item_quantity' => $_POST['quantity']
    );

    $_SESSION['shopping_cart'][0] = $item_array;
  }
}

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'delete') {
    foreach($_SESSION['shopping_cart'] as $keys => $values){
      if ($values['item_id'] == $_GET['id']) {
        unset($_SESSION['shopping_cart'][$keys]);
        echo '<script>alert("item Removed")</script>';
        echo '<script>window.location="index.php"</script>';
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Home</title>
  </head>
  <body>
    <div class="container">
      <nav id="navbar">
        <h3>Shopping Cart</h3>
      </nav>
      <div id="output">
        <?php
          $query = 'SELECT * FROM store ORDER BY id ASC';

          $result = mysqli_query($conn, $query);
          if (mysqli_num_rows($result)  > 0) {
            while ($row = mysqli_fetch_array($result)) {
              ?>
              <div class="col-md-4">
                <form class="form" action="index.php?add&id=<?php echo $row['id']; ?>" method="post">
                  <img src="<?php echo $row['image']; ?>" class="image-responsive">
                  <h4><?php echo $row['name']; ?></h4>
                  <h4 class="color"><?php echo $row['price']; ?> </h4>
                  <input type="text" name="quantity" value="1">
                  <input type="hidden" name="hidden_name" value="<?php echo $row['name']; ?>">
                  <input type="hidden" name="hidden_price" value="<?php echo $row['price']; ?>">
                  <input id="btn" type="submit" name="add_to_cart" value="Add to Cart">
                </form>
              </div>
              <?php
            }
          }
        ?>
      </div>
    </div>
    <div style="clear:both"></div>
    <br><h3 id="order">Order Details</h3>
<div class="table">
  <table id="table">
    <tr>
      <th width="40%">Item Name</th>
      <th width="10">Quantity</th>
      <th width="20%">Priced</th>
      <th width="15%">Total</th>
      <th width="5%">Action</th>
    </tr>
    <?php
      if (!empty($_SESSION['shopping_cart'])) {
        $total = 0;
        foreach($_SESSION['shopping_cart'] as $keys=> $values){
          ?>
          <tr>
            <td><?php echo $values['item_name']; ?></td>
            <td><?php echo $values['item_quantity']; ?></td>
            <td>$ <?php echo $values['item_price']; ?></td>
            <td><?php echo number_format($values['item_quantity'] * $values['item_price'], 2); ?></td>
            <td><a href="index.php?action=delete&id=<?php echo $values['item_id']; ?>"><span class="danger">Remove</span></a></td>
          </tr>
          <?php
            $total = $total + ($values['item_quantity'] * $values['item_price']);
        }
        ?>
          <tr>
            <td colspan="3" align="right">Total</td>
            <td align="right">$ <?php echo number_format($total, 2); ?></td>
          </tr>
        <?php
      }
    ?>
  </table>
</div>

    <script src="js/app.js" charset="utf-8"></script>
  </body>
</html>
