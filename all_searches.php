<?php

require_once "database.php";
require_once "update_database.php";
$statement_all = $pdo->prepare("SELECT * FROM animes");
$statement_all->execute();
$all_animes = $statement_all->fetchAll(PDO::FETCH_ASSOC);

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
    <header class="container mx-auto my-3">
      <a class="inline-block py-1 rounded-md text-lime-600 hover:text-white text-xl" href="index.php">Go back</a>
      <a class="inline-block py-1 ml-10 rounded-md text-gray-700 hover:text-white text-lg" href="clear.php">Clear database</a>
    </header>
    <?php if (!empty($all_animes)): ?>
      <section class="container bg-gray-200 mx-auto my-8 p-8">
        <div class="grid grid-cols-4">
          <?php foreach ($all_animes as $anime): ?>
            <div class="p-4 border-2 border-white">
              <div class="w-full h-80">
                <img src="<?= $anime["image"] ?>" class="w-full h-full">
              </div>
              <div class="w-full flex flex-col">
                <p class="my-6 text-center text-2xl font-bold"><?= $anime["title_eng"] ?></p>
                <p class="w-full"><?= $anime["synopsis"] ?></p>
                <?php if ($anime["yt_url"]): ?>
                  <a href="<?= $anime["yt_url"] ?>" class="inline-block text-gray-500 hover:text-lime-600 mt-3" target="_blank">See trailer</a>
                <?php endif ?>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </section>
    <?php else: ?>
      <section class="container mx-auto">
      <p class="text-center text-2xl text-lime-700">No items to display</p>
      </section>
    <?php endif ?>
  </body>
</html>