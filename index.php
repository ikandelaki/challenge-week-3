<?php

// Connection with database
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=anime_list', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement_fetch = $pdo->prepare('SELECT * FROM animes');
$statement_fetch->execute();
$animes = $statement_fetch->fetchAll(PDO::FETCH_ASSOC);

// Variables to fetch data
$anime = str_ireplace(' ', '-', $_POST['anime']);
$base_url = "https://api.jikan.moe/v4/anime?q=";

// Setting up curl to fetch data
$curl = curl_init($base_url . $anime);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = json_decode(curl_exec($curl), true);

// Storing the result in a 'data' variable
$data = $result["data"];

foreach ($data as $datum) {
  $img_url = $datum["images"]["jpg"]["large_image_url"];
  file_put_contents("./images/" . basename($img_url), file_get_contents($img_url));
  $statement_write = $pdo->prepare("INSERT INTO animes (title, synopsis, yt_url, image) 
      VALUES (:title, :synopsis, :yt_url, :image)");
  $statement_write->bindValue(':title', $datum["title"]);
  $statement_write->bindValue(':synopsis', $datum["synopsis"]);
  $statement_write->bindValue(':yt_url', $datum["trailer"]["url"]);
  $statement_write->bindValue(':image', "./images/" . basename($img_url));

  $statement_write->execute();
  unlink("./images/" . basename($img_url));
};
?>

<!doctype html>
  <html>
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
      
      <link href="/dist/output.css" rel="stylesheet">
    </head>
  <body>
    
    <header>
      <span class="absolute block p-8 text-gray-400 font-bold tracking-widest">Your favourite anime portal</span>
      <form method="post" action="./index.php" class="flex flex-col items-center 
      justify-center gap-3 text-lg py-8">
        <label for="anime" class="text-lime-600 mb-2 text-2xl font-bold">Search anime</label>
        <input type="text" name="anime" id="anime" placeholder="Naruto" class="py-1 px-2 border-gray-300 border-2 rounded-md hover:border-gray-400 transition-all duration-300">
        <button type="submit" class="bg-lime-600 py-1 px-2 rounded-md text-white hover:bg-lime-700
        transition-all duration-300">Search</button>
      </form>
    </header>

    <!-- If the result is an empty array, meaning anime was not found, throw an error message -->
    <?php if (isset($_POST) && empty($data)): ?>
      <p class="text-center text-2xl text-lime-700">Anime you entered was not found</p>
    <?php endif ?>
      <section class="container bg-gray-200 mx-auto my-8 p-8">

      <?php 
      print "<pre>";
      print_r($data);
      print "</pre>";
      ?>
    </section>

  </body>
</html>