<!DOCTYPE html>
<html>
<head>
  <title>Doctor-G</title>
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
    
    /* Styles to hide the content initially */
    #content {
      display: none;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.search-form').submit(function(event) {
        var searchInput = $('#search-input').val().trim();
        if (searchInput === '') {
          alert('Please enter a search query.');
          event.preventDefault(); // Prevent form submission
        }
      });
    });
  </script>
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
  <div id="content">
  <div class="header">
    <img src="logo2.png" alt="Logo" class="logo">
    <div class="menu">
      <button class="add-disease-button" onclick="window.location.href='add_new.php'">Add New Disease</button>
    </div>
  </div>

  <div class="content">
    <div class="search-container">
      <h2>Search</h2>
      <form class="search-form" action="" method="GET">
    <input class="search-input" type="text" id="search-input" name="search" placeholder="Search">
    <input class="search-button" type="submit" value="Search">
</form>
      <div id="suggestions-container"></div>
    </div>

    <div class="search-results-container">
         
    <?php
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'doctor';
    $tableName = 'diseases';

    $conn = mysqli_connect($host, $username, $password, $database);

    // Check if the connection was successful
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Check if the search query is empty
    if (empty($searchQuery)) {
        echo '<p>Please enter a search query.</p>';
    } else {
        // Prepare the SQL statement
        $sql = "SELECT * FROM diseases WHERE name LIKE '%$searchQuery%'";

        // Execute the SQL query
        $queryResult = mysqli_query($conn, $sql);

        // Process the query results and display them
        if (mysqli_num_rows($queryResult) > 0) {
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $name = $row['name'];
                $description = $row['description'];
                $prevention = $row['prevention'];
                $medicines = $row['medicines'];
                $image = $row['image'];

                // Output the background image dynamically
                echo '<style>';
                echo 'body {';
                echo '  background-image: url("images/' . $image . '");'; // Adjust the path as per your folder structure
                echo '}';
                echo '</style>';

                echo '<div class="search-result">';
                echo '<h2>' . $name . '</h2>';
                echo '<p><strong>Description:</strong> ' . $description . '</p>';
                echo '<p><strong>Prevention:</strong> ' . $prevention . '</p>';
                echo '<p><strong>Medicines:</strong> ' . $medicines . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No results found for "' . $searchQuery . '".</p>';
        }

        // Close the database connection
        mysqli_close($conn);
    }
}
?>

        </div>
      </div>
  </div>

  <script>
  $(document).ready(function() {
    // Handle input in the search box
    $('#search-input').on('input', function() {
      var query = $(this).val();
      if (query.length >= 1) {
        // AJAX call to fetch suggestions
        $.ajax({
          url: 'search_suggestions.php', // Update the URL path if needed
          type: 'GET',
          data: { query: query },
          success: function(response) {
            var suggestions = JSON.parse(response);
            var suggestionsContainer = $('#suggestions-container');
            suggestionsContainer.empty();
            var list = $('<ul></ul>'); // Create a list element
            suggestions.forEach(function(suggestion) {
              var listItem = $('<li class="suggestion"></li>').text(suggestion); // Create list item
              list.append(listItem); // Append list item to the list
            });
            suggestionsContainer.append(list); // Append the list to the suggestions container
          }
        });
      } else {
        $('#suggestions-container').empty();
      }
    });

    // Handle click on a suggestion
    $(document).on('click', '.suggestion', function() {
      var suggestion = $(this).text();
      $('#search-input').val(suggestion); // Paste the suggestion into the search box
      $('#suggestions-container').empty(); // Clear the suggestions container
    });

  });
</script>

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
        }, 3000);
      }
    };
  </script>
</body>
</html>
