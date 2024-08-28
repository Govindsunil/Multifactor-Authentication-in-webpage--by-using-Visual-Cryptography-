<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
    </style>
  </head>

  <body>
    <div class="container">
      <form method="POST" action="split.php">
        <h1 id="recap">Registration</h1>

        <div class="child1">
          <h1>Register</h1>

          <input type="text" id="text" name="text" placeholder="Enter your secret code" />

          <div>
            <input type="submit" value="Split into Share" />

            <hr />

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
        </div>
      </form>
    </div>
  </body>
</html>
