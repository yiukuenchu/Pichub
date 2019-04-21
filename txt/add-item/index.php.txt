<!DOCTYPE html>
<html>
  <head>
    <?php 
      DEFINE("PAGE_TITLE", "Add Item");
      require('../partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/item.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
      <?php include('../partials/navbar.php'); ?>
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4">Add New Item</h1>
        </div>
      </div>
      <div class="container">
        <?php 
          if (filter_has_var(INPUT_POST, "name")){
            addItem();
          } else {
            displayForm();
          }
          
          function displayForm() {
            print <<<HERE
              <form action="" method="POST">
                <fieldset>
                  <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" type="text" placeholder="Milky Way Galaxy" required>
                  </div>
                  <div class="form-group">
                    <label>Photographer</label>
                    <input class="form-control" name="photographer" type="text" placeholder="John Smith" required>
                  </div>
                  <div class="form-group">
                    <label>Category</label>
                    <input class="form-control" name="category" type="text" placeholder="space" required>
                  </div>
                  <div class="form-group">
                    <label>Color</label>
                    <input class="form-control" name="color" type="text" placeholder="blue" required>
                  </div>
                  <div class="form-group">
                    <label>Image URL</label>
                    <input class="form-control" name="imageURL" type="url" placeholder="https://source.unsplash.com/..." required>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="5" maxlength="500" required></textarea>
                  </div>
                </fieldset>
                <div class="form-group">
                  <button class="btn btn-success" type="submit">Add Item</button>
                </div>
              </form>
HERE;
          }
        
          function addItem() {
            @$db = new mysqli(HOSTNAME, USERNAME, PASSWORD, USERNAME);
            if (mysqli_connect_errno()) {
              echo '<h4 class="text-danger">Error: Could not connect to database.</h4>';
              exit;
            }
            
            $query = "INSERT INTO products VALUES(NULL, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssssss', $_POST['name'], $_POST['photographer'], $_POST['category'], $_POST['color'], $_POST['imageURL'], $_POST['description']);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
              echo  '<h4 class="text-success">Item successfully added!</h4>';
            } else {
              echo '<h4 class="text-danger">Error adding the item. Please try again!</h4>';
            }
            $db->close();
          }
        ?>
      </div>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>