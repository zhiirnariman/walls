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

// Set headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Handle GET request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Check if an ID was provided
  if (isset($_GET["id"])) {
    // Retrieve a single wallpaper by ID
    $id = intval($_GET["id"]);

    $result = $conn->query("SELECT * FROM wallpapers WHERE id = $id");

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      http_response_code(200);
      echo json_encode($row);
    } else {
      http_response_code(404);
      echo json_encode(array("message" => "Wallpaper not found"));
    }
  } else {
    // Retrieve all wallpapers
    $result = $conn->query("SELECT * FROM wallpapers");

    $wallpapers = array();

    while ($row = $result->fetch_assoc()) {
      array_push($wallpapers, $row);
    }

    http_response_code(200);
    echo json_encode($wallpapers);
  }
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve POST data
  $wallpaper = $conn->real_escape_string($_POST["wallpaper"]);
  $category = $conn->real_escape_string($_POST["category"]);

  // Insert new wallpaper into database
  $result = $conn->query("INSERT INTO wallpapers (wallpaper, wall_category) VALUES ('$wallpaper', '$category')");

  if ($result) {
    http_response_code(201);
    echo json_encode(array("message" => "Wallpaper created successfully"));
  } else {
    http_response_code(500);
    echo json_encode(array("message" => "Unable to create wallpaper"));
  }
}

// Handle PUT request
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
  // Retrieve PUT data
  parse_str(file_get_contents("php://input"), $put_data);
  $id = intval($put_data["id"]);
  $wallpaper = $conn->real_escape_string($put_data["wallpaper"]);
  $category = $conn->real_escape_string($put_data["category"]);

  // Update wallpaper in database
  $result = $conn->query("UPDATE wallpapers SET wallpaper = '$wallpaper', wall_category = '$category' WHERE id = $id");

  if ($result) {
    http_response_code(200);
    echo json_encode(array("message" => "Wallpaper updated successfully"));
  } else {
    http_response_code(
        500);
        echo json_encode(array("message" => "Unable to update wallpaper"));
        }
        }
        
        // Handle DELETE request
        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        // Retrieve DELETE data
        parse_str(file_get_contents("php://input"), $delete_data);
        $id = intval($delete_data["id"]);
        
        // Delete wallpaper from database
        $result = $conn->query("DELETE FROM wallpapers WHERE id = $id");
        
        if ($result) {
        http_response_code(200);
        echo json_encode(array("message" => "Wallpaper deleted successfully"));
        } else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to delete wallpaper"));
        }
        }
        
        // Close database connection
        $conn->close();
        
        ?>