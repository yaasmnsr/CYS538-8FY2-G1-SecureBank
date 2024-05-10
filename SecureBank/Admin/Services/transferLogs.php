<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Transfer Logs</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

  <script>
    // Function to filter the table based on the selected values
    function filterTable() {
      let senderFilter = document.getElementById("sender-filter").value;
      let recipientFilter = document.getElementById("recipient-filter").value;

      let table, rows, i;
      table = document.getElementById("transfers-table");
      rows = table.rows;
      for (i = 1; i < rows.length; i++) {
        let senderCell = rows[i].getElementsByClassName("sender-account")[0];
        let recipientCell = rows[i].getElementsByClassName("recipient-account")[0];

        if (
          senderCell.innerHTML.includes(senderFilter) &&
          recipientCell.innerHTML.includes(recipientFilter)
        ) {
          rows[i].style.display = "";
        } else {
          rows[i].style.display = "none";
        }
      }
    }

    function exportToExcel() {
      const table = document.getElementById("transfers-table");
      const rows = Array.from(table.getElementsByTagName("tr"));

      // Create a new Excel workbook
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet("Transfers");

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
        a.download = "Transfers.xlsx";
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
    <h1 style="margin-bottom: 10px;">Transfer Logs</h1>
    <div class="filters-container">
      <div>
        <label class="filter-label" for="sender-filter">Filter by Sender Account Number:</label>
        <select id="sender-filter" onchange="filterTable()">
          <option value="">All</option>
          <?php
          include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

          // Prepare the query to fetch sender account numbers
          $senderQuery = "SELECT DISTINCT a1.account_number 
                          FROM fundstransfer t
                          INNER JOIN accounts a1 ON t.sender_accountid = a1.account_id";
          $senderResult = mysqli_query($conn, $senderQuery);

          // Check if the query executed successfully
          if ($senderResult) {
            // Loop through the rows of the result set
            while ($row = mysqli_fetch_assoc($senderResult)) {
              echo "<option value='" . $row['account_number'] . "'>" . $row['account_number'] . "</option>";
            }

            // Free the result set
            mysqli_free_result($senderResult);
          } else {
            // Handle the case when the query fails
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </select>
      </div>
      <div>
        <label class="filter-label" for="recipient-filter">Filter by Recipient Account Number:</label>
        <select id="recipient-filter" onchange="filterTable()">
          <option value="">All</option>
          <?php
          // Prepare the query to fetch recipient account numbers
          $recipientQuery = "SELECT DISTINCT a2.account_number 
                             FROM fundstransfer t
                             INNER JOIN accounts a2 ON t.recipient_accountid = a2.account_id";
          $recipientResult = mysqli_query($conn, $recipientQuery);

          // Check if the query executed successfully
          if ($recipientResult) {
            // Loop through the rows of the result set
            while ($row = mysqli_fetch_assoc($recipientResult)) {
              echo "<option value='" . $row['account_number'] . "'>" . $row['account_number'] . "</option>";
            }

            // Free the result set
            mysqli_free_result($recipientResult);
          } else {
            // Handle the case when the query fails
            echo "Error: " . mysqli_error($conn);
          }

          mysqli_close($conn);
          ?>
        </select>
      </div>
    </div>
    <button class="button" style="height: 35px;" onclick="exportToExcel()">Export to Excel</button>

    <table id="transfers-table">
      <thead>
        <tr>
          <th>Transfer ID</th>
          <th>Sender Account Number</th>
          <th>Recipient Account Number</th>
          <th>Transfer Amount</th>
          <th>Transfer Time</th>
        </tr>
      </thead>
      <tbody>
      <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

        // Prepare the query
        $query = "SELECT t.transfer_id, a1.account_number AS sender_account_number, a2.account_number AS recipient_account_number, t.transfer_amount, t.transfer_time
                  FROM fundstransfer t
                  INNER JOIN accounts a1 ON t.sender_accountid = a1.account_id
                  INNER JOIN accounts a2 ON t.recipient_accountid = a2.account_id";
        $result = mysqli_query($conn, $query);

        // Check if the query executed successfully
        if ($result) {
          // Check if there are any rows in the result set
          if (mysqli_num_rows($result) > 0) {
            // Loop through the rows of the result set
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['transfer_id'] . "</td>";
              echo "<td class='sender-account'>" . $row['sender_account_number'] . "</td>";
              echo "<td class='recipient-account'>" . $row['recipient_account_number'] . "</td>";
              echo "<td>" . number_format($row['transfer_amount'], 2) . "</td>";
              echo "<td>" . $row['transfer_time'] . "</td>";
              echo "</tr>";
            }
          } else {
            // Display "No transfers exist" message
            echo "<tr><td colspan='5'>No transfers exist</td></tr>";
          }

          // Free the result set
          mysqli_free_result($result);
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