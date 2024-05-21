<?php
          // Parameters for database connection
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "acm tracker";
          // Create connection with the parameters
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Checking the connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve user input from the form
        

        $Name = $_POST['Name'];
        $CF_handles =$_POST['CF_handles'];

        // Prepare SQL query to insert user data into the 'participate' table
        $sql = "INSERT INTO `participate`(`Name`, `CF_handles`) VALUES ('$Name','$CF_handles')";
        $query=mysqli_query($conn,$sql);

        if($query){
            header("location:dashboard.html");
        }
        // Execute SQL query
        // if ($conn->query($sql) === TRUE) {
        //     echo "New record created successfully";
        //     header("location:dashboard.html");
        // } else {
        //     echo "Error: " . $sql . "<br>". $conn->error;
        // }

        // Close database connection
        // $conn->close();


?>

