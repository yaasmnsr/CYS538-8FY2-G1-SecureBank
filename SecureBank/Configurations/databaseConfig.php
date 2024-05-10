<?php
    // Create a Connection
    $conn = mysqli_connect('localhost', 'Yasmeen', '123456', 'bank');

    // Check Connection
    if (mysqli_connect_errno())
    {
        // Connection Failed
        echo 'Failed to connect to MYSQL' . mysqli_connect_errno();
    }
?>