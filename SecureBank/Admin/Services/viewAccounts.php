<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - View Accounts</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

  <script>
    // Function to filter the table based on the selected value
    function filterTable(column, value) {
      let table, rows, i;
      table = document.getElementById("account-table");
      rows = table.rows;
      for (i = 1; i < rows.length; i++) {
        let cell = rows[i].getElementsByClassName(column)[0];
        if (cell.innerHTML.includes(value) || value === "") {
          rows[i].style.display = "";
        } else {
          rows[i].style.display = "none";
        }
      }
    }

    // Function to search the table by User ID
    function searchUser() {
      let input, filter, table, rows, cell, i;
      input = document.getElementById("search-input");
      filter = input.value.toUpperCase();
      table = document.getElementById("account-table");
      rows = table.getElementsByTagName("tr");
      for (i = 1; i < rows.length; i++) {
        cell = rows[i].getElementsByTagName("td")[6];
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
      filterTable('account-status', '');
      filterTable('account-type', '');
      for (let i = 0; i < document.getElementById("account-table").rows.length; i++) {
        document.getElementById("account-table").rows[i].style.display = "";
      }
    }

    function exportToExcel() {
      const table = document.getElementById("account-table");
      const rows = Array.from(table.getElementsByTagName("tr"));

      // Create a new Excel workbook
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet("Accounts");

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
        a.download = "Accounts.xlsx";
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
    <h1 style="margin-bottom: 10px;">View Accounts</h1>
    <div style="text-align: center;">
        <input type="text" id="search-input" placeholder="Search by User ID">
        <button class="button" style="height: 35px;" onclick="searchUser()">Search</button>
        <button class="button" style="height: 35px;" onclick="clearSearch()">Clear Search</button>
    </div>
    <button class="button" style="height: 35px;" onclick="exportToExcel()">Export to Excel</button>
    <div class="filters-container">
        <div>
            <label class="filter-label" for="status-filter">Filter by Account Status:</label>
            <select id="status-filter" onchange="filterTable('account-status', this.value)">
                <option value="">All</option>
                <option value="Active">Active</option>
                <option value="Pending Approval">Pending Approval</option>
                <option value="Suspended">Suspended</option>
                <option value="Closed">Closed</option>
            </select>
        </div>
        <div>
            <label class="filter-label" for="type-filter">Filter by Account Type:</label>
            <select id="type-filter" onchange="filterTable('account-type', this.value)">
                <option value="">All</option>
                <option value="Savings">Savings Account</option>
                <option value="Checking">Checking Account</option>
                <option value="Business">Business Account</option>
                <option value="Individual Retirement">Individual Retirement Account</option>
            </select>
        </div>
    </div>
    
    <table id="account-table">
      <thead>
        <tr>
          <th>Account ID</th>
          <th>Account Number</th>
          <th>Account Type</th>
          <th>Date Opened</th>
          <th>Account Status</th>
          <th>Balance</th>
          <th>User ID</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

        // Prepare the query
        $query = "SELECT a.account_id, a.account_number, a.account_type, a.date_opened, a.account_status, a.balance, a.user_id
                    FROM accounts a";
        $statement = mysqli_prepare($conn, $query);

        // Execute the query
        mysqli_stmt_execute($statement);

        // Bind the result variables
        mysqli_stmt_bind_result($statement, $account_id, $account_number, $account_type, $date_opened, $account_status, $balance, $user_id);

        // Fetch all rows into an array
        $accounts = array();
        while (mysqli_stmt_fetch($statement)) {
            $account = array(
                'account_id' => $account_id,
                'account_number' => $account_number,
                'account_type' => $account_type,
                'date_opened' => $date_opened,
                'account_status' => $account_status,
                'balance' => $balance,
                'user_id' => $user_id
            );
            $accounts[] = $account;
        }

        // Check if the array is empty
        if (empty($accounts)) {
          echo "<tr><td colspan='7'>No accounts exist.</td></tr>";
        } else {
          // Loop through the accounts and display the details
          foreach ($accounts as $account) {
            echo "<tr>";
            echo "<td>" . $account['account_id'] . "</td>";
            echo "<td>" . $account['account_number'] . "</td>";
            echo "<td class='account-type'>" . $account['account_type'] . "</td>";
            echo "<td class='date-opened'>" . $account['date_opened'] . "</td>";
            echo "<td class='account-status'>" . $account['account_status'] . "</td>";
            echo "<td>" . number_format($account['balance'], 2) . "</td>";
            echo "<td>" . $account['user_id'] . "</td>";
            echo "</tr>";
          }
        }

        mysqli_stmt_close($statement);
        mysqli_close($conn);
        ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>

</body>
</html>