<?php
require 'vendor/autoload.php';



// Sprawdzenie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['text_input'])) {
  $text = $_POST['text_input'];

  // Walidacja długości tekstu
  if (strlen($text) > 100) {
    die('Tekst jest zbyt długi.');
  }

  // Sanityzacja danych
  $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

  $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
  $barcodeData = $generator->getBarcode($text, $generator::TYPE_CODE_128);

  // Zapisanie kodu kreskowego jako JPEG
  $timestamp = time();
  $jpgPath = 'barcode_' . $timestamp . '.jpg';
  $webpPath = 'barcode_' . $timestamp . '.webp';

  file_put_contents($jpgPath, $barcodeData);

  // Konwersja JPEG do WebP
  $image = imagecreatefromjpeg($jpgPath);
  imagewebp($image, $webpPath);
  imagedestroy($image);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generator kodu kreskowego</title>
</head>

<body>
  <form action="index.php" method="post">
    <label for="text_input">Wprowadź tekst do generowania kodu kreskowego:</label>
    <input type="text" id="text_input" name="text_input" required>
    <input type="submit" value="Generuj kod kreskowy">
  </form>

  <?php
  if (isset($text)) {
    echo "<h2>Wygenerowany kod kreskowy:</h2>";
    echo "<img src='" . $webpPath . "' alt='Kod kreskowy'>";
  }
  ?>
</body>

</html>