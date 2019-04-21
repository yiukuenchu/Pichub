<?php
  session_start();
  // User must be logged in and an Admin to view this page
  if(!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../");
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define("PAGE_TITLE", "All Orders");
      require('../../partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/admin.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
      <?php
        include('../../partials/navbar.php');
        include('../../partials/adminSidebar.php');
        require('../../partials/database.php');
      ?>
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4">All Orders</h1>
        </div>
      </div>
      <div class="container" id="vue">
        <?php
          // Updates the order status when submitted from modal
          if(isset($_POST) && isset($_POST['orderID']) && is_numeric($_POST['orderID'])) {
            $updateQuery = "UPDATE FramedOrders
                            SET status = '{$_POST['status']}'
                            WHERE orderID = {$_POST['orderID']}";
            $successful = mysqli_query($connection, $updateQuery);
            if($successful) {
              echo '<h4 class="text-success">Successfully updated status!</h4>';
            } else {
              echo '<h4 class="text-danger">Error updating status!</h4>';
            }
          }
        ?>
        <button class="btn btn-link" @click="filterBox = !filterBox">Filter <span class="fas fa-chevron-down"></span></button>
        <!-- Filtering options for orders -->
        <form action="" method="get" v-show="filterBox" id="filterForm">
          <div class="form-inline my-3 mx-3">
            <div class="form-group mx-2">
              <label>Status</label>
              <select class="form-control mx-2" name="status" @input="updateFilter">
                <option value="">Any</option>
                <option <?php if(!empty($_GET['status']) && $_GET['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                <option <?php if(!empty($_GET['status']) && $_GET['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option <?php if(!empty($_GET['status']) && $_GET['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                <option <?php if(!empty($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
              </select>
            </div>
            <div class="form-check mx-2 mb-2">
              <input class="form-check-input" type="checkbox" value="" name="desc" <?php if(isset($_GET['desc'])) echo 'checked'; ?> @input="updateFilter">
              <label class="form-check-label" for="desc">
                Sort in descending order
              </label>
            </div>
            <div class="form-group">
              <a class="btn btn-dark ml-2" href="."><span class="fas fa-times"></span> Clear</a>
            </div>
          </div>
        </form>
        <!-- Query to show orders -->
        <?php
          $orderQuery = "SELECT orderID, name, shippingMethod, status, timestamp FROM FramedOrders";
          if(isset($_GET) && !empty($_GET['status'])) {
            // Check to make sure that valid status is being requested
            $statusOptions = ["Processing", "Shipped", "Delivered", "Cancelled"];
            $statusFilter = (in_array($_GET['status'], $statusOptions)) ? $_GET['status'] : null;
          }

         $orderQuery .= (isset($statusFilter)) ? " WHERE status = '{$_GET['status']}'" : ""; // Add status filter to query
         $orderQuery .= (isset($_GET['desc'])) ? " ORDER BY orderID DESC" : ""; // Add descending order if set
         $orderQuery .= ";";
         $orders = mysqli_query($connection, $orderQuery);
         if (!$orders) {
           echo '<h4 class="text-danger">Error loading past orders</h4>';
         } else if(mysqli_num_rows($orders) == 0) {
           echo '<h4 class="text-info">No matching orders found.</h4>';
         } else {
           echo '<div class="table-responsive"><table class="table"><tr><th>Order #</th><th>Customer</th><th>Shipping</th><th>Status</th><th>Date Ordered</th></tr>';
           while($row = mysqli_fetch_assoc($orders)) {
             $dateString = date('M j, Y g:i A', strtotime($row['timestamp'])); // Make timestamp more readable
             echo <<<HERE
             <tr>
               <td><a class="btn btn-link text-primary" @click="openOrderModal({$row['orderID']})">{$row['orderID']}</a></td>
               <td>{$row['name']}</td>
               <td>{$row['shippingMethod']}</td>
               <td>{$row['status']}</td>
               <td>{$dateString}</td>
             </tr>
HERE;
           }
           echo "</table></div>";
           mysqli_free_result($orders);
         }
         mysqli_close($connection);
       ?>
       <!-- Modal that shows order info. Also uses BootstrapVue and Vue.js -->
       <b-modal ref="orderModal" title="Order Details" @ok="submitForm" ok-title="Save" tabindex="-1" role="dialog" aria-hidden="true">
         <h6><strong>Order #:</strong> {{ orderInfo.orderID }}</h6>
         <h6><strong>Shipping Address:</strong></h6>
         <p class="mb-0">{{ orderInfo.name }}</p>
         <p class="mb-0">{{ orderInfo.stAddress }}</p>
         <p class="mb-0">{{ orderInfo.stAddress2 }}</p>
         <p>{{ orderInfo.city }}, {{ orderInfo.state }} {{ orderInfo.zip}}</p>
         <h6><strong>Shipping Method:</strong> {{ orderInfo.shippingMethod }}</h6>
         <h6><strong>Phone:</strong> {{ orderInfo.phone }}</h6>
         <h6><strong>Items Ordered</strong></h6>
         <div class="table-responsive">
           <table class="table">
             <tr>
               <th>Name</th>
               <th>Photographer</th>
               <th>Frame</th>
               <th>Description</th>
             </tr>
             <tr v-for="item in orderInfo.items">
               <td><a :href="'<?php echo $_ENV['SERVER_ROOT'];?>/item?id=' + item.productID" target="_blank">{{ item.name }}</a></td>
               <td>{{ item.photographer }}</td>
               <td>{{ item.frame }}</td>
               <td>{{ item.description }}</td>
             </tr>
           </table>
         </div>
         <h6><strong>Order Date:</strong> {{ orderInfo.timestamp }}</h6>
         <h6><strong>Order Status</strong></h6>
         <!-- Form used to change the order status -->
         <form action="" method="POST" id="form">
           <input type="hidden" name="orderID" :value="orderInfo.orderID">
           <select class="form-control" name="status">
             <option :selected="orderInfo.status === 'Processing'">Processing</option>
             <option :selected="orderInfo.status === 'Shipped'">Shipped</option>
             <option :selected="orderInfo.status === 'Delivered'">Delivered</option>
             <option :selected="orderInfo.status === 'Cancelled'">Cancelled</option>
           </select>
         </form>
         <br>
       </b-modal>
     </div>
    </div>
    <?php include('../../partials/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script src="<?php path('/js/orderInfo.js'); ?>"></script>
  </body>
</html>
