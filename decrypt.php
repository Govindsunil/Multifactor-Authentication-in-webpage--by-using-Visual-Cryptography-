<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    $share2 = $_FILES['share2']['tmp_name'];
    if (!empty($username)) {
        if (!empty($password)) {
            $host = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $dbname = "youtube";

            // Create connection
            $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

            // Check if email already exists
            $check_query = "SELECT * FROM youtube WHERE username = '$username'";
            $result = $conn->query($check_query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $storedpassword = $row["password"];
                $storedtext = $row["text"];
                $storedid = $row["id"];
                $share1 = $row["share1image"];

                if ($password === $storedpassword) {
                    // Create image resource from the share1 file
                    $canvas1 = imagecreatefromstring($share1);
                    $canvas2 = imagecreatefromstring(file_get_contents($share2));

                    if ($canvas1 !== false && $canvas2 !== false) {
                        $width1 = imagesx($canvas1);
                        $height1 = imagesy($canvas1);

                        $width2 = imagesx($canvas2);
                        $height2 = imagesy($canvas2);

                        $length = $height1;
                        $message = "";

                        for ($i = 0; $i < $length; $i++) {
                            for ($j = 0; $j < $width1; $j++) {
                                if ($j < $width1 && $i < $height1 && $j < $width2 && $i < $height2) {
                                    $rgb1 = imagecolorat($canvas1, $j, $i);
                                    $pixel1 = ($rgb1 >> 16) & 0xFF;

                                    $rgb2 = imagecolorat($canvas2, $j, $i);
                                    $pixel2 = ($rgb2 >> 16) & 0xFF;

                                    $bit = ($pixel1 + $pixel2) % 2;
                                    $message .= $bit;
                                } 
                                // else {
                                //     echo "Coordinates out of bounds uplode the correct key";
                                // }
                            }
                        }

                        $message = preg_replace('/^1+/', '', $message);
                        $bytes = str_split($message, 8);
                        $decodedMessage = "";

                        foreach ($bytes as $byte) {
                            $ascii = bindec($byte);
                            $decodedMessage .= chr($ascii);
                        }

                        $filteredMessage = preg_replace('/[^a-zA-Z0-9\s\!\@\#\$\%\^\&\*\(\)\_\+\-\=\{\}\[\]\|\\\:\;\"\'\<\>\,\.\?\/]+/', '', $decodedMessage);

                        echo "hai   " . $filteredMessage;
                                        if ($filteredMessage === $storedtext) {
                                        // Message is correct
                                        echo '<script>alert("Login successful"); </script>';
                                    } else {
                                        // Message is incorrect
                                        echo "Incorrect error share image";
                                    }
                        
                    } else {
                        // Failed to create image resources
                        echo "Failed to create image resources from share1 or share2 files";
                    }
                } else {
                    // Incorrect password
                    echo "Incorrect password";
                }
            } else {
                // Email or username not found in the database
                echo "Incorrect email or username";
            }
        } else {
            // Password is empty
            echo "Password should not be empty";
        }
    } else {
        // Username is empty
        echo "Username should not be empty";
    }

    // Close database connection
    $conn->close();
}

?>
