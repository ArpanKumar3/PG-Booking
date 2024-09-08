<?php
$conn = mysqli_connect("localhost","root","","pg_life");

if (mysqli_connect_error()) {
    echo "Failed to connect to database";
    return;
}
?>