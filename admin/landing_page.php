<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>الرئيسية | المسؤول</title>
    <script src="../shared/header.js" defer></script>
    <link rel="stylesheet" href="../shared/header.css" />
  </head>
  <body>
    <!-- Chart -->
    <div class="chartBox" id="container">
      <canvas id="chart"></canvas>
    </div>

    <!-- get product data from database -->
    <?php
        require("../config/connection.php");

        $query = "SELECT product_name, SUM(quantity) AS total_quantity FROM \"Cart_Item\" GROUP BY product_name";
    $result = pg_query($conn, $query);

        if (pg_num_rows($result) > 0) {
            $ordersData = array();
            while ($row = pg_fetch_assoc($result)) {
                // Get the product name and total quantity
        $productName = $row['product_name'];
        $totalQuantity = $row['total_quantity'];

        // Store the product name and its total quantity
        $ordersData[] = array("product_name" => $productName, "total_quantity" => $totalQuantity);
            }
            //if you wanna see how the array look like uncomment this
            #echo print_r($ordersData, true); //true so the function doesn't return 1 at the end
        } else {
            echo '<p No orders Have been made yet</p>';
        }
        ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      // Get data from php
            //Convert php array to something readable/understood for js (which is json)
            const ordersData = <?php echo json_encode($ordersData); ?>;

            // Prepare data for the chart
      const labels = ordersData.map(item => item.product_name); // Get product names
      const dataValues = ordersData.map(item => item.total_quantity); // Get total quantities

      // Generate random colors for each product (you can customize this as needed)
      const backgroundColors = dataValues.map(() => `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.2)`);
      const borderColors = backgroundColors.map(color => color.replace('0.2', '1'));

            // Setup chart
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Times Ordered',
                    data: dataValues,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            };

            // Config chart
            const config = {
                type: 'doughnut',
                data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            // Render chart
            const chart = new Chart(
                document.getElementById("chart"),
                config
            );
    </script>
  </body>
</html>
