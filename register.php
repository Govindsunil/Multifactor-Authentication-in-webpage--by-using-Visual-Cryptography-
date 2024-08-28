<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $message = filter_input(INPUT_POST, 'text');
 $username = filter_input(INPUT_POST, 'email');
 $password = filter_input(INPUT_POST, 'password');

  $message = $_POST['text'];
  $length = strlen($message);
  $pixels = $length * 8;
  $share1 = [];
  $share2 = [];

  for ($i = 0; $i < $pixels; $i++) {
    $bit = (ord(substr($message, floor($i / 8), 1)) >> (7 - ($i % 8))) & 1;
    $share1[$i] = mt_rand(0, 1);
    $share2[$i] = ($share1[$i] + $bit) % 2;
  }

 function generateImage($data, $width, $height) {
  $canvas = imagecreatetruecolor($width, $height);

  for ($i = 0; $i < count($data); $i++) {
    $x = $i % $width;
    $y = floor($i / $width);
    $color = $data[$i] ? imagecolorallocate($canvas, 0, 0, 0) : imagecolorallocate($canvas, 255, 255, 255);
    imagesetpixel($canvas, $x, $y, $color);
  }

  ob_start();
  imagepng($canvas);
  $imageData = ob_get_clean();

  imagedestroy($canvas);

  return $imageData;
}



  function generateDataUrl($data, $width, $height) {
    $imageData = generateImage($data, $width, $height);
    $base64Data = base64_encode($imageData);
    $dataUrl = 'data:image/png;base64,' . $base64Data;
    return $dataUrl;
  }

  $share1Image = generateImage($share1, floor($pixels / 8), $length);
  $share1DownloadUrl = generateDataUrl($share1, floor($pixels / 8), $length);
  $share2Image = generateImage($share2, floor($pixels / 8), $length);
  $share2DownloadUrl = generateDataUrl($share2, floor($pixels / 8), $length);
 
 
  
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "youtube";

// Retrieve input values
$username = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'passwords');
$text = filter_input(INPUT_POST, 'text');
$image = generateImage($share1, floor($pixels / 8), $length);

// Check if input values are not empty
if (!empty($username) && !empty($password) && !empty($text) && !empty($image)) {
    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    // Check if email already exists
    $check_query = "SELECT * FROM youtube WHERE username = '$username'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        $message = "Email is already registered. Please enter a different email";
        echo '<script>alert("' . $message . '");
         window.location.href = "index.html";
         </script>';
        die();
    }

    // Read the image file
    // $imageData = addslashes(file_get_contents($image));

    // Insert user data and image into the database
    $sql = "INSERT INTO youtube (username, password, text, share1image) values ('$username', '$password', '$text', '$image')";
    if ($conn->query($sql)) {
        // Redirect to login page
        $message = "Registration successful!";
        echo '<script>alert("' . $message . '");
        </script>';
        // echo '<script>alert("' . $message . '"); 
        // window.location.href = "login.html";
        // </script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    $message = "Username, password, text, and image should not be empty";
        echo '<script>alert("' . $message . '");
         window.location.href = "index.html";
         </script>';
        die();
}
}
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <title>miniproject</title>
    <link rel="stylesheet" href="register.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
      rel="stylesheet"
    />
    <style>
      input[type="submit"] {
        background: #fff;
        width: 100%;
        height: 45px;
        outline: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: #f72d7a;
        font-size: 16px;
        color: #fff;
        box-shadow: rgba(0, 0, 0, 0.4);
      }
    </style>
  </head>
  <body>
    <!-- NAVBAR CREATION -->
    <header class="header">
      <nav class="navbar">
        <a href="index.html">Home</a>
        <a href="https://media.kasperskydaily.com/wp-content/uploads/sites/85/2021/11/15135955/Cybersecurity-Portfolio-for-Business-Catalogue-1.pdf">Portfolio</a>
        <a href="https://en.m.wikipedia.org/wiki/Login">About</a>
        <a href="https://instagram.com/secure_login_help000?igshid=NGExMmI2YTkyZg==">Contact</a>
        <a href="https://www.synopsys.com/blogs/software-security/cryptography-best-practices/">Help</a>
        <button class="btnLogin-popup">Login</button>
      </nav>
      <form action="" class="search-bar">
        <input type="text" placeholder="Search..." />
        <button><i class="bx bx-search"></i></button>
      </form>
    </header>
    
    <!-- SIGN UP FORM CREATION -->

    <script src="index.js"></script>
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
    <script></script>
  </body>
</html>
