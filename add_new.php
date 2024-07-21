<!DOCTYPE html>
<html>
<head>
  <title>ADD NEW FORM</title>
  <link rel="apple-touch-icon" sizes="76x76" href="logo.png">
  <link rel="icon" href="logo.png">
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
  <style>
    /* Styles for the loading div */
    #loading {
      display: block;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      color: #fff;
      text-align: center;
      padding-top: 20%;
    }
    
    .form-container {
    background-color: #f2f2f2;
    padding: 20px;
    margin-bottom: 20px;
    margin-right:0;

  }

    /* Styles to hide the content initially */
    #content {
      display: none;
    }
  </style>
</head>
<body>
  <!-- Loading div -->
  <div id="loading">
    <div class="container">
        <div class="preloader">
           <span></span>
           <span></span>
           <span></span>
        </div>
        <div class="shadow"></div>
     </div>
  </div>

  <!-- Your page content -->
  <div id="content" >
    <div class="header">
        <img src="logo2.png" alt="Logo" class="logo">
        <div class="menu">
            <button class="add-disease-button" onclick="window.location.href='index.php'">back</button>
        </div>
      </div>
    

      <div class="content">
        <div class="form-container">
            <h2>Add New Disease</h2>
            <form method="POST" enctype="multipart/form-data">
              <label for="name">Name:</label>
              <input class="form-input" type="text" id="name" name="name" required>
        
              <label for="image">Image:</label>
              <input class="form-input" type="file" id="image" name="image" accept="image/*" required>

              <label for="description">Description:</label>
              <textarea class="form-input" id="description" name="description" rows="4" required></textarea>
        
              <label for="prevention">Prevention:</label>
              <textarea class="form-input" id="prevention" name="prevention" rows="4" required></textarea>
        
              <label for="medicines">Medicines:</label>
              <textarea class="form-input" id="medicines" name="medicines" rows="4" required></textarea>
        
              <input class="form-button" type="submit" value="Add Disease">
            </form>
          

  <script>
    // JavaScript to show the loading div and hide it after 3 seconds
    document.onreadystatechange = function () {
      var loadingDiv = document.getElementById('loading');
      var contentDiv = document.getElementById('content');
      
      if (document.readyState === 'loading') {
        loadingDiv.style.display = 'block';
      } else if (document.readyState === 'interactive') {
        setTimeout(function() {
          loadingDiv.style.display = 'none';
          contentDiv.style.display = 'block';
        }, 2000);
      }
    };
  </script>

<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = "root"; // Change this to your database password
$database = "doctor";

// Establishing the database connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data and escape special characters
  $name = mysqli_real_escape_string($conn, $_POST["name"]);
  $description = mysqli_real_escape_string($conn, $_POST["description"]);
  $prevention = mysqli_real_escape_string($conn, $_POST["prevention"]);
  $medicines = mysqli_real_escape_string($conn, $_POST["medicines"]);

  // Upload image file
  if(isset($_FILES['image'])) {
    $image = $_FILES["image"]["name"];
    $temp_image = $_FILES["image"]["tmp_name"];
    $image_folder = "images/"; // Create a folder named "images" to store uploaded images
    if(move_uploaded_file($temp_image, $image_folder.$image)) {
      // Insert data into the database
      $sql = "INSERT INTO diseases (name, description, prevention, medicines, image) VALUES ('$name', '$description', '$prevention', '$medicines', '$image')";
  
      if (mysqli_query($conn, $sql)) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    echo "No file selected.";
  }
}

// Closing database connection
mysqli_close($conn);
?>
</body>
</html>
