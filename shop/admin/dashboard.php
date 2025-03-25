<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Fetch admin profile details
$fetch_profile = null; // Initialize variable
$select_profile = $conn->prepare("SELECT name FROM `admins` WHERE id = ?");
$select_profile->execute([$admin_id]);

if($select_profile->rowCount() > 0){
   $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
   $fetch_profile = ['name' => 'Admin']; // Fallback value if profile not found
}

// Fetch number of admins
$select_admins = $conn->prepare("SELECT COUNT(*) as count FROM `admins`");
$select_admins->execute();
$number_of_admins = $select_admins->fetch(PDO::FETCH_ASSOC)['count'];

// Fetch number of users
$select_users = $conn->prepare("SELECT COUNT(*) as count FROM `users`");
$select_users->execute();
$number_of_users = $select_users->fetch(PDO::FETCH_ASSOC)['count'];

// Fetch number of products by category
$select_categories = $conn->prepare("SELECT category, COUNT(*) as count FROM `products` GROUP BY category");
$select_categories->execute();
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);

// Calculate total order values
$total_pendings = 0;
$select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['pending']);
if($select_pendings->rowCount() > 0){
   while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
      $total_pendings += $fetch_pendings['total_price'];
   }
}

$total_completes = 0;
$select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['completed']);
if($select_completes->rowCount() > 0){
   while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
      $total_completes += $fetch_completes['total_price'];
   }
}

$total_orders_value = $total_pendings + $total_completes;
$pending_percentage = $total_orders_value > 0 ? ($total_pendings / $total_orders_value) * 100 : 0;
$completed_percentage = $total_orders_value > 0 ? ($total_completes / $total_orders_value) * 100 : 0;

// Fetch sales data
$select_sales = $conn->prepare("SELECT total_price, placed_on FROM `orders` WHERE payment_status = 'completed'");
$select_sales->execute();
$sales = $select_sales->fetchAll(PDO::FETCH_ASSOC);

// Calculate average sales
$total_sales = 0;
$day_sales = [];
$month_sales = [];
$year_sales = [];

foreach ($sales as $sale) {
    $total_sales += $sale['total_price'];
    $day = date('Y-m-d', strtotime($sale['placed_on']));
    $month = date('Y-m', strtotime($sale['placed_on']));
    $year = date('Y', strtotime($sale['placed_on']));

    if (!isset($day_sales[$day])) {
        $day_sales[$day] = 0;
    }
    $day_sales[$day] += $sale['total_price'];

    if (!isset($month_sales[$month])) {
        $month_sales[$month] = 0;
    }
    $month_sales[$month] += $sale['total_price'];

    if (!isset($year_sales[$year])) {
        $year_sales[$year] = 0;
    }
    $year_sales[$year] += $sale['total_price'];
}

$average_day_sales = $total_sales / count($day_sales);
$average_month_sales = $total_sales / count($month_sales);
$average_year_sales = $total_sales / count($year_sales);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Bootstrap 5 CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- FontAwesome Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <link rel="stylesheet" href="../css/admin_style.css">
   
</head>
<body>
<?php include '../components/admin_header.php'; ?>


<div class="main-content">
<h1 class="heading">Dashboard</h1>

   <div class="row">
      <!-- Welcome Card -->
      <div class="col-md-3 mb-4">
         <div class="card text-center p-3 shadow-sm">
            <i class="fas fa-user icon text-primary"></i>
            <h5 class="mt-3">Welcome!</h5>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn btn-primary btn-sm">Update Profile</a>
         </div>
      </div>

      <!-- Total Pendings -->
      <div class="col-md-3 mb-4">
         <div class="card text-center p-3 shadow-sm">
            <i class="fas fa-clock icon text-warning"></i>
            <h3>Rs. <?= $total_pendings; ?>/-</h3>
            <p>Total Pendings</p>
            <a href="placed_orders.php" class="btn btn-warning btn-sm">See Orders</a>
         </div>
      </div>

      <!-- Completed Orders -->
      <div class="col-md-3 mb-4">
         <div class="card text-center p-3 shadow-sm">
            <i class="fas fa-check-circle icon text-success"></i>
            <h3>Rs. <?= $total_completes; ?>/-</h3>
            <p>Completed Orders</p>
            <a href="placed_orders.php" class="btn btn-success btn-sm">See Orders</a>
         </div>
      </div>

      <!-- Orders Placed -->
      <div class="col-md-3 mb-4">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
         ?>
         <div class="card text-center p-3 shadow-sm">
            <i class="fas fa-shopping-cart icon text-info"></i>
            <h3><?= $number_of_orders; ?></h3>
            <p>Orders Placed</p>
            <a href="placed_orders.php" class="btn btn-info btn-sm">See Orders</a>
         </div>
      </div>
   </div>

   <!-- Combined Progress Bar for Orders -->
   <div class="row mb-4">
      <div class="col-md-12">
         <div class="card p-4">
            <h5 class="mb-3">Orders Progress</h5>
            <div class="progress">
               <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $pending_percentage; ?>%;" aria-valuenow="<?= $pending_percentage; ?>" aria-valuemin="0" aria-valuemax="100"><?= round($pending_percentage, 2); ?>% Pending</div>
               <div class="progress-bar bg-success" role="progressbar" style="width: <?= $completed_percentage; ?>%;" aria-valuenow="<?= $completed_percentage; ?>" aria-valuemin="0" aria-valuemax="100"><?= round($completed_percentage, 2); ?>% Completed</div>
            </div>
         </div>
      </div>
   </div>

   <!-- Graphs Row -->
   <div class="row">
      <!-- Orders Overview Chart -->
      <div class="col-md-6 mb-4">
         <div class="card p-4" id="ordersChartContainer">
            <h5 class="mb-3">Orders Overview</h5>
            <canvas id="ordersChart"></canvas>
         </div>
      </div>

      <!-- Admins vs Users Pie Chart -->
      <div class="col-md-6 mb-4">
         <div class="card p-4" id="adminsUsersChartContainer">
            <h5 class="mb-3">Admins vs Users</h5>
            <canvas id="adminsUsersChart"></canvas>
         </div>
      </div>
   </div>

   <!-- Products by Category Chart -->
   <div class="row">
      <div class="col-md-12 mb-4">
         <div class="card p-4" id="productsChartContainer">
            <h5 class="mb-3">Products by Category</h5>
            <canvas id="productsChart"></canvas>
         </div>
      </div>
   </div>

   <!-- Average Sales Chart -->
   <div class="row">
      <div class="col-md-12 mb-4">
         <div class="card p-4" id="salesChartContainer">
            <h5 class="mb-3">Average Sales</h5>
            <canvas id="salesChart"></canvas>
         </div>
      </div>
   </div>
</div>

<script>
   // Chart.js Integration for Orders
   const ctxOrders = document.getElementById('ordersChart').getContext('2d');
   new Chart(ctxOrders, {
      type: 'bar',
      data: {
         labels: ['Total Pendings', 'Completed Orders'],
         datasets: [{
            label: 'Order Values (Rs.)',
            data: [<?= $total_pendings; ?>, <?= $total_completes; ?>],
            backgroundColor: ['#ffc107', '#28a745'],
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: { display: true }
         },
         hover: {
            mode: 'nearest',
            intersect: true
         },
         interaction: {
            mode: 'nearest',
            intersect: true
         }
      }
   });

   // Chart.js Integration for Admins vs Users
   const ctxAdminsUsers = document.getElementById('adminsUsersChart').getContext('2d');
   new Chart(ctxAdminsUsers, {
      type: 'pie',
      data: {
         labels: ['Admins', 'Users'],
         datasets: [{
            label: 'Count',
            data: [<?= $number_of_admins; ?>, <?= $number_of_users; ?>],
            backgroundColor: ['#007bff', '#dc3545'],
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: { display: true }
         },
         hover: {
            mode: 'nearest',
            intersect: true
         },
         interaction: {
            mode: 'nearest',
            intersect: true
         }
      }
   });

   // Chart.js Integration for Products by Category
   const ctxProducts = document.getElementById('productsChart').getContext('2d');
   new Chart(ctxProducts, {
      type: 'line',
      data: {
         labels: <?= json_encode(array_column($categories, 'category')); ?>,
         datasets: [{
            label: 'Number of Products',
            data: <?= json_encode(array_column($categories, 'count')); ?>,
            borderColor: '#17a2b8',
            fill: false,
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: { display: true }
         },
         hover: {
            mode: 'nearest',
            intersect: true
         },
         interaction: {
            mode: 'nearest',
            intersect: true
         }
      }
   });

   // Chart.js Integration for Average Sales
   const ctxSales = document.getElementById('salesChart').getContext('2d');
   new Chart(ctxSales, {
      type: 'bar',
      data: {
         labels: ['Average Day Sales', 'Average Month Sales', 'Average Year Sales'],
         datasets: [{
            label: 'Average Sales (Rs.)',
            data: [<?= $average_day_sales; ?>, <?= $average_month_sales; ?>, <?= $average_year_sales; ?>],
            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: { display: true }
         },
         hover: {
            mode: 'nearest',
            intersect: true
         },
         interaction: {
            mode: 'nearest',
            intersect: true
         }
      }
   });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>