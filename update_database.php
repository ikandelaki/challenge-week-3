<?php 

// Do some logic when the anime was input by the user
if (!empty($_POST['anime'])) {

  $anime = str_ireplace(' ', '-', $_POST['anime']);
  $base_url = "https://api.jikan.moe/v4/anime?q=";
  
  // Setting up curl to fetch data
  $curl = curl_init($base_url . $anime);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $result = json_decode(curl_exec($curl), true);
  
  // Storing the result in a 'data' variable
  $data = $result["data"];
  
  if (!empty($data)) {    
    // Storing the fetched data into a database
    foreach ($data as $datum) {
      $title = $datum['title'];
      $statement = $pdo->prepare("SELECT * FROM animes WHERE title_eng = ?");
      $statement->execute([$title]);
      $result = $statement->rowCount();

      if ($result != 1) {
        $img_url = $datum["images"]["jpg"]["large_image_url"];
        file_put_contents("./images/" . basename($img_url), file_get_contents($img_url));
        $statement_write = $pdo->prepare("INSERT INTO animes (title, title_eng, synopsis, yt_url, image) 
          VALUES (:title, :title_eng, :synopsis, :yt_url, :image)");
        $statement_write->bindValue(':title', $anime);
        $statement_write->bindValue(':title_eng', $datum["title"]);
        $statement_write->bindValue(':synopsis', $datum["synopsis"]);
        $statement_write->bindValue(':yt_url', $datum["trailer"]["url"]);
        $statement_write->bindValue(':image', "./images/" . basename($img_url));
        $statement_write->execute();
      } else {
        continue;
      }
    };
  }
  // Getting the search results 
  $statement_fetch = $pdo->prepare('SELECT * FROM animes WHERE title like :keyword');
  $statement_fetch->bindValue(":keyword", $anime);
  $statement_fetch->execute();
  $animes = $statement_fetch->fetchAll(PDO::FETCH_ASSOC);
}

?>