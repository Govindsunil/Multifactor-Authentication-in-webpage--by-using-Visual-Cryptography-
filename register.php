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
$password = filter_input(INPUT_POST, 'password');
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
        echo '<script>alert("' . $message . '"); window.location.href = "new.html";</script>';
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
    echo "Username, password, text, and image should not be empty";
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
        <a href="#">Home</a>
        <a href="#">Portfolio</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#">Help</a>
        <button class="btnLogin-popup">Login</button>
      </nav>
      <form action="" class="search-bar">
        <input type="text" placeholder="Search..." />
        <button><i class="bx bx-search"></i></button>
      </form>
    </header>
    <!-- LOGIN FORM CREATION -->
    <div class="background"></div>
    <div class="container  active-popup">
      <span class="icon-close"><ion-icon name="close-outline"></ion-icon></span>
      <div class="item">
        <h2 class="logo"><i class="bx bxl-xing"></i>Mini Project</h2>
        <div class="text-item">
          <h2>Welcome! <br /><span> To Our Project </span></h2>
          <p>Luck runs out but safety is good for life.</p>
          <div class="social-icon">
            <a href="#"><i class="bx bxl-facebook"></i></a>
            <a href="#"><i class="bx bxl-twitter"></i></a>
            <a href="#"><i class="bx bxl-youtube"></i></a>
            <a href="#"><i class="bx bxl-instagram"></i></a>
            <a href="#"><i class="bx bxl-linkedin"></i></a>
          </div>
        </div>
      </div>
      <div class="login-section active">
        <div class="form-box login">
           <form method="post" action="decrypt.php" enctype="multipart/form-data">
            <h2>Sign In</h2>
            <div class="input-box">
              <span class="icon"><i class="bx bxs-envelope"></i></span>
               <input type="email" id="email" name="email" required />
              <label>Email</label>
            </div>
            <div class="input-box">
              <div style="position: relative">
                <span class="icon"><i class="bx bxs-lock-alt"></i></span>
               <input type="password" id="password" name="password"required />
                <label>Password</label>
                <span class="eye" onclick="eyekey()">
                  <i id="hide1" class="fa fa-eye"></i>
                  <i id="hide2" class="fa fa-eye-slash"></i>
                </span>
              </div>
            </div>
            <div class="remember-password">
              <label for=""><input type="checkbox" />Remember Me</label>
              <a href="#">Forget Password</a>
            </div>
            <input type="button" value="NEXT" onclick="openModal()" />
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>

                <h1>Upload your shares</h1>
                <!-- <p>Upload your two shares below:</p> -->
                <!-- <input type="file" id="share1" accept="image/*" /> -->

                <input type="file" id="share2" name="share2" accept="image/*" />

                <input
                  type="submit"
                 
                  value="Upload the image"
                />

              </div>
            </div>
            <div class="create-account">
              <p>
                Create A New Account?
                <a href="#" class="register-link">Sign Up</a>
              </p>
            </div>
          </form>
        </div>
        <div class="form-box register">
          <form method="POST" action="register.php">
            <h2>Sign Up</h2>

            <div class="input-box">
              <span class="icon"><i class="bx bxs-envelope"></i></span>
              <input type="email" id="email" name="email" required />
              <label>Email</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class="bx bxs-lock-alt"></i></span>
              <input type="password" id="password" name="password" required />
              <label>Password</label>
            </div>

            <!-- <div class="input-box">
              <span class="icon"><i class="bx bxs-user"></i></span>
              <input type="text" id="text" name="text" required />
              <label>Enter the secret code</label>
            </div> -->

            <div class="remember-password">
              <label for=""
                ><input type="checkbox" />I agree with this statment</label
              >
            </div>
            <input type="button" value="NEXT" onclick="openModal1()" />
            <div id="myModal1" class="modal1"style="display: block;">
              <div class="modal-content1">
                <span class="close" onclick="closeModal1()">&times;</span>

                <h1>Create login key</h1>
                <div class="input-box">
                  <span class="icon"><i class="bx bxs-user"></i></span>
                  <input type="text" id="text" name="text" required />
                  <label>Enter the secret code</label>
                </div>

                <div>
            <input type="submit" value="Split into Share" />

            <div id="share1">
              <?php if (isset($share1Image)) : ?>
                <img src="data:image/png;base64,<?php echo base64_encode($share1Image); ?>" alt="Share 1" />
              <?php endif; ?>
            </div>
            <a id="download1" href="<?php echo isset($share1DownloadUrl) ? $share1DownloadUrl : ''; ?>" download="share1.png" class="download-button">Download Share 1</a>

            <div id="share2">
              <?php if (isset($share2Image)) : ?>
                <img src="data:image/png;base64,<?php echo base64_encode($share2Image); ?>" alt="Share 2" />
              <?php endif; ?>
            </div>
            <a id="download2" href="<?php echo isset($share2DownloadUrl) ? $share2DownloadUrl : ''; ?>" download="share2.png" class="download-button">Download Share 2 </a>
          </div>

                <!-- <label for="message">Result:</label> -->
                <div id="message"></div>
                <input type="hidden" name="result" id="result" readonly />
              </div>
            </div>
            <div class="create-account">
              <p>
                Already Have An Account?
                <a href="#" class="login-link">Sign In</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
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
