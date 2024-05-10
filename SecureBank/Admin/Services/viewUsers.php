<?php
include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

// Prepare the query
$query = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.phone, u.city, u.district, u.street, COUNT(a.account_id) AS num_accounts
          FROM users u
          LEFT JOIN accounts a ON u.user_id = a.user_id
          GROUP BY u.user_id";
$statement = mysqli_prepare($conn, $query);

// Execute the query
mysqli_stmt_execute($statement);

// Bind the result variables
mysqli_stmt_bind_result($statement, $user_id, $first_name, $last_name, $email, $phone, $city, $district, $street, $num_accounts);

// Fetch the rows
$rows = array();
while (mysqli_stmt_fetch($statement)) {
    $rows[] = array(
        'user_id' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'district' => $district,
        'street' => $street,
        'num_accounts' => $num_accounts
    );
}

mysqli_stmt_close($statement);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - View Users</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

  <script>
    // Function to search the table by User ID
    function searchUser() {
      let input, filter, table, rows, cell, i;
      input = document.getElementById("search-input");
      filter = input.value.toUpperCase();
      table = document.getElementById("user-table");
      rows = table.getElementsByTagName("tr");
      for (i = 1; i < rows.length; i++) {
        cell = rows[i].getElementsByTagName("td")[0];
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

      let table = document.getElementById("user-table");
      let rows = table.getElementsByTagName("tr");
      for (let i = 1; i < rows.length; i++) {
        rows[i].style.display = "";
      }
    }

    function exportToExcel() {
      const table = document.getElementById("user-table");
      const rows = Array.from(table.getElementsByTagName("tr"));

      // Create a new Excel workbook
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet("Users");

      // Add table headers
      const headers = rows[0].getElementsByTagName("th");
      const headerValues = Array.from(headers).map((header) => header.textContent);
      worksheet.addRow(headerValues);

      // Add table rows
      rows.slice(1).forEach((row) => {
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
        a.download = "Users.xlsx";
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
    <h1 style="margin-bottom: 10px;">Registered Users</h1>
    <div style="text-align: center;">
      <input type="text" id="search-input" placeholder="Search by User ID">
      <button class="button" style="height: 35px;" onclick="searchUser()">Search</button>
      <button class="button" style="height: 35px;" onclick="clearSearch()">Clear Search</button>
    </div>
    <button class="button" style="height: 35px;" onclick="exportToExcel()">Export to Excel</button>
    <br>
    <table id="user-table">
      <thead>
        <tr>
          <th>User ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>District</th>
          <th>Street</th>
          <th>Number of Accounts</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($rows) > 0) { 
          foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['city'] . "</td>";
            echo "<td>" . $row['district'] . "</td>";
            echo "<td>" . $row['street'] . "</td>";
            echo "<td>" . $row['num_accounts'] . "</td>";
            echo "</tr>";
          }
        } else {  // If the table was empty.
          echo "<tr><td colspan='9'>No users exist.</td></tr>";
        } ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>

</html>