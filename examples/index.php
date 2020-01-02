<?php

include 'bootstrap.php';


$db = new \db\SPDO(DB_HOST);

$tableExist = $db->tableInfo('records');
if(!$tableExist)
    die('Table "records" not exists!');


$menu = $db->select('id, link, title', 'records','active=?', 1);


$contentBlog = false;
$contentRec = false;
if(isset($_GET['rec']) && ($link = $_GET['rec'])){

    #simple
    //$contentRec = $db->select('id, title, description','records','link=:link',[':link'=>$link], false);

    #adv
    $sql = "
        SELECT r.id, r.title, r.description, u.name, u.email
        FROM records r
        LEFT JOIN users u ON (u.id = r.iduser)
        WHERE r.link=:link";

    $stat = $db->prepare($sql);
    $stat->bindParam(':link',$link,PDO::PARAM_STR);
    $stat->execute();
    $contentRec = $stat->fetch();

}else{

    $contentBlog = $records = $db->select('*', 'records','active=?', 1);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <div class="header"><h1>Blog</h1></div>
        <div class="menu">
            <div class="menu-admin table text-center">
                <a href="/index.php">Main page</a>
                <a href="/admin.php">Create new</a>
            </div>
            <?php foreach($menu as $item): ?>
                <p class="table">
                    <a href="/index.php?rec=<?= $item['link']?>"><?= $item['title']?></a>
                    <a href="/admin.php?edit=<?= $item['link']?>">[edit]</a>
                </p>
            <?php endforeach; ?>
        </div>
        <div class="content">
            <?php if($contentRec):?>

            <div class="record">
                <div class="back"><a href="/index.php">Back</a></div>
                <h2><?= $contentRec['title']?></h2>
                <div class="desc"><?= $contentRec['description']?></div>
                <div class="author">Author: <?= $contentRec['name']?> | <?= $contentRec['email']?></div>
            </div>

            <?php else: ?>

                <?php foreach ($contentBlog as $record): ?>
                    <div class="records">
                        <h2><a href="/index.php?rec=<?= $record['link']?>"><?= $record['title']?></a></h2>
                        <div class="desc"><?= $record['description']?></div>
                        <div class="more"><a href="/index.php?rec=<?= $record['link']?>">read more...</a></div>
                    </div>
                <?php endforeach;?>

            <?php endif;?>
        </div>
        <div class="footer">
            Use php class SPDO. Create 2015. example blog.
        </div>
    </div>

</body>
</html>

<?php
#Show error text if there are
if($error = $db->getError()){
    echo 'Driver error: '.$error['error'].'<br />';
    echo 'SQL: '.$error['sql'].'<br />';
}
