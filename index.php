<?php


define('DSN', 'mysql:host=db;dbname=pet_shop;charset=utf8;');
define('USER', 'staff');
define('PASSWORD', '9999');

try {
    $dbh = new PDO(DSN, USER, PASSWORD);
} catch (PDOException $e) {
    // 接続がうまくいかない場合こちらの処理がなされる
    echo $e->getMessage();
    exit;
}


// 値の受け取り
$keyword = $_GET["keyword"];

if (is_null($keyword))
{
    $sql = 'SELECT * FROM animals';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else 
{
    $keyword = '%' . $keyword . '%';
    // SQL文の組み立て
    $sql = 'SELECT * FROM animals WHERE description LIKE :keyword';
    // プリペアドステートメントの準備
    // $dbh->query($sql) でも良い
    $stmt = $dbh->prepare($sql);
    //変数をバインドする
    $stmt->bindValue(":keyword", $keyword, PDO::PARAM_STR);
    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の受け取り
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>pet_shop</title>
    <meta name="description" content="ペットの検索" />
</head>

<body>
    <h1>本日のご紹介ペット！</h1>
    <div>
        <form method="get">
            <label for="keyword">キーワード:</label>
            <input type="text" name="keyword" placeholder="キーワードの入力">
            <input type="submit" value="検索">
        </form>
    </div>
    <br>
    <div>
        <?php foreach ($animals as $animal) : ?>
            <?= $animal['type'] . 'の' . $animal['classification'] . 'ちゃん<br>' ?>
            <?= $animal['description'] . '<br>' ?>
            <?= $animal['birthday'] . '生まれ<br>' ?>
            <?= '出身地' . $animal['birthplace'] ?>
            <hr>
        <?php endforeach; ?>
    </div>
</body>

</html>