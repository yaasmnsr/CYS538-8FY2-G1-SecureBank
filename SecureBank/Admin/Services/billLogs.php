<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Bill Payment Logs</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

  <script>
    // Function to search the table by User ID
    function searchUser() {
      let input, filter, table, rows, cell, i;
      input = document.getElementById("search-input");
      filter = input.value.toUpperCase();
      table = document.getElementById("bill-payment-table");
      rows = table.getElementsByTagName("tr");
      for (i = 1; i < rows.length; i++) {
        cell = rows[i].getElementsByTagName("td")[1];
        if (cell) {
          if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
            rows[i].style.display = "";
          } else {
            rows[i].style.display = "none";
          }
        }
      }
    }

    // Function to clear the search input and display all rows
    function clearSearch() {
      let input = document.getElementById("search-input");
      input.value = "";

      let table = document.getElementById("bill-payment-table");
      let rows = table.getElementsByTagName("tr");
      for (let i = 1; i < rows.length; i++) {
        rows[i].style.display = "";
      }
    }

    function exportToExcel() {
      const table = document.getElementById("bill-payment-table");
      const rows = Array.from(table.getElementsByTagName("tr"));

      // Create a new Excel workbook
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet("Bill Payments");

      // Add table headers
      const headers = rows.shift().getElementsByTagName("th");
      const headerValues = Array.from(headers).map((header) => header.textContent);
      worksheet.addRow(headerValues);

      // Add table rows
      rows.forEach((row) => {
        const cells = row.getElementsByTagName("td");
        const cellValues = Array.from(cells).map((cell) => cell.textContent);
        worksheet.addRow(cellValues);
      });

      // Generate the Excel file
      workbook.xlsx.writeBuffer().then((buffer) => {
        const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Bills.xlsx";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
      });
    }
  </script>
</head>

<body>
  <?php include('../Include/header.php'); ?>

  <main>
    <h1 style="margin-bottom: 10px;">Bill Payment Logs</h1>
    
    <div style="text-align: center;">
        <input type="text" id="search-input" placeholder="Search by User ID">
        <button class="button" style="height: 35px;" onclick="searchUser()">Search</button>
        <button class="button" style="height: 35px;" onclick="clearSearch()">Clear Search</button>
    </div>
    <button class="button" style="height: 35px;" onclick="exportToExcel()">Export to Excel</button>

    <table id="bill-payment-table">
      <thead>
        <tr>
          <th>Bill ID</th>
          <th>User ID</th>
          <th>Bill Type</th>
          <th>Amount</th>
          <th>Issued Date</th>
          <th>Status</th>
          <th>Account Number</th>
          <th>Payment Time</th>
        </tr>
      </thead>
      <tbody>
      <?php
        require('../../Configurations/databaseConfig.php');

        // Fetch bill payment logs from the database
        $query = "SELECT * FROM billpayments";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
          // Check if there are any rows in the result set
          if (mysqli_num_rows($result) > 0) {
            // Loop through the retrieved rows
            while ($row = mysqli_fetch_assoc($result)) {
              // Retrieve the account number associated with the account ID
              $accountQuery = "SELECT account_number FROM accounts WHERE account_id = '{$row['account_id']}'";
              $accountResult = mysqli_query($conn, $accountQuery);

              if ($accountResult && mysqli_num_rows($accountResult) > 0) {
                $accountRow = mysqli_fetch_assoc($accountResult);
                $accountNumber = $accountRow['account_number'];
              } else {
                $accountNumber = '-';
              }

              // Display the row in the table
              echo "<tr>";
              echo "<td>{$row['bill_id']}</td>";
              echo "<td>{$row['user_id']}</td>";
              echo "<td>{$row['bill_type']}</td>";
              echo "<td>" . number_format($row['amount'], 2) . "</td>";
              echo "<td>{$row['issued_date']}</td>";
              echo "<td>{$row['status']}</td>";
              echo "<td>$accountNumber</td>";
              echo "<td>{$row['payment_time']}</td>";
              echo "</tr>";
            }

            // Free the result set
            mysqli_free_result($result);
          } else {
            // Display "No bills exist" message
            echo "<tr><td colspan='8'>No bills exist</td></tr>";
          }
        } else {
          // Handle the case when the query fails
          echo "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
        ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>
</body>

</html>