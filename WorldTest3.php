<!DOCTYPE html>
<html>
<head>
    <title>Country Information</title>
    <style>
        /* Styling for the navigation bar */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Styling for the country information section */
        #country-info {
            margin-top: 20px;
        }

        #country-info h2 {
            margin-bottom: 10px;
        }

        #country-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- NavBar -->
<div class="navbar">
        <a href="WorldTest3.php">Home</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
    </div>

    <h1>Country Information</h1>

    <form method="post">
        <label for="country">Select a Country:</label>
        <select name="country" id="country">
            <?php
            // Establish a MySQL connection
            $servername = "localhost";
            $username = "root";
            $password = "@Mysqlse2023";
            $dbname = "world";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch country names from the database
            $sql = "SELECT Name FROM country";
            $result = $conn->query($sql);

            // Generate dropdown options
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
            }

            // Close the MySQL connection
            $conn->close();
            ?>
        </select>
        <button type="submit">Submit</button>
    </form>

    <div id="country-info">
        <?php
        // Check if a country is selected
        if (isset($_POST['country'])) {
            $selectedCountry = $_POST['country'];

            // Establish a new MySQL connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve country information based on the selected country
            $sql = "SELECT * FROM country WHERE Name = '$selectedCountry'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Display country information
                $countryData = $result->fetch_assoc();

                echo "<h2>{$countryData['Name']}</h2>";
                echo "<p><strong>Continent:</strong> {$countryData['Continent']}</p>";
                echo "<p><strong>Region:</strong> {$countryData['Region']}</p>";
                echo "<p><strong>Surface Area:</strong> {$countryData['SurfaceArea']} m<sup>2</sup></p>";
                echo "<p><strong>Population:</strong> {$countryData['Population']}</p>";
                echo "<p><strong>Life Expectancy:</strong> {$countryData['LifeExpectancy']} years</p>";

                // Retrieve capital from country table
                $capitalID = $countryData['Capital'];
                $sql = "SELECT Name FROM city WHERE ID = $capitalID";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $capitalData = $result->fetch_assoc();
                    echo "<p><strong>Capital:</strong> {$capitalData['Name']}</p>";
                } else {
                    echo "<p><strong>Capital:</strong> Not Available</p>";
                }

                // Retrieve official languages
                $countryCode = $countryData['Code'];
                $sql = "SELECT Language FROM countrylanguage WHERE CountryCode = '$countryCode' AND IsOfficial = 'T'";
                $result = $conn->query($sql);
                $officialLanguages = array();
                while ($row = $result->fetch_assoc()) {
                    $officialLanguages[] = $row['Language'];
                }

                // Retrieve unofficial languages
                $sql = "SELECT Language FROM countrylanguage WHERE CountryCode = '$countryCode' AND IsOfficial = 'F'";
                $result = $conn->query($sql);
                $unofficialLanguages = array();
                while ($row = $result->fetch_assoc()) {
                    $unofficialLanguages[] = $row['Language'];
                }

                echo "<p><strong>Official Languages:</strong> " . implode(", ", $officialLanguages) . "</p>";
                echo "<p><strong>Unofficial Languages:</strong> " . implode(", ", $unofficialLanguages) . "</p>";
            } else {
                echo "<p>No country found with the name '$selectedCountry'.</p>";
            }

            // Close the MySQL connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
