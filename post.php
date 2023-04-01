<?php
// Set up database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "api_walls";

$conn = new mysqli($host, $user, $password, $database);

// Check for errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve POST data
  $wallpaper = $conn->real_escape_string($_POST["wallpaper"]);
  $wall_category = $conn->real_escape_string($_POST["wall_category"]);

  // Insert data into table
  $sql = "INSERT INTO Wallpapers (wallpaper, wall_category) VALUES ('$wallpaper', '$wall_category')";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
  // Close database connection
  $conn->close();
}
?>
