<?php

include('../db/SPDO.php');


$db = new SPDO('sqlite:database.sqlite');

$tableExist = $db->tableInfo('records');
if(!$tableExist)
    die('Table "records" not exists!');


$menu = $db->select('id,link,title', 'records','active=?',1);


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

    $contentBlog = $records = $db->select('*', 'records','active=?',1);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        *{margin:0;padding:0;}
        .page{
            width: 860px;
            margin: 0 auto;
            font-family: 'Ubuntu Condensed', 'Ubuntu', sans-serif;
            font-size: 90%;
        }
        .header{
            height: 40px;
            text-align: center;
            border-bottom: 2px solid cadetblue;
        }
        .menu{
            display: inline-block;
            width: 25%; padding: 10px 0;
            border-right: 2px solid cadetblue;
            background: cadetblue;
        }
        .menu a{
            display: block;
            padding: 3px 10px;
            background: cadetblue;
            font-size: 75%;
            color: aliceblue;
            text-decoration: none;
        }
        .menu a:hover{
            background: #236264;
            color: aliceblue;
        }
        .content{
            display: inline-block;
            width: 74%;
            float: right;
        }
        .footer{
            padding-top: 30px;
            padding-bottom: 30px;
            clear: both;
            text-align: center;
            font-size: 75%;
            font-weight: bold;
        }

        .record{}
        .back{
            text-align: right;
        }
        .back a{
            display: inline-block;
            padding: 2px 15px;
            background: cadetblue;
            text-decoration: none;
            font-weight: bold;
            color: aliceblue;
        }
        .record h2{
            padding-bottom: 10px;
        }
        .record .desc{
            padding-bottom: 10px;}
        .record .author{
            font-size: 80%;
            font-weight: bold;
            color: indigo;
        }

        .records{
            padding: 5px 0;
            border-bottom: 1px solid cadetblue;
        }
        .records h2 a{
            color: #236264;
            text-decoration: none;
        }
        .records .desc{
            font-size: 80%;
            padding: 5px 0 5px 25px;
        }
        .records .more{
            text-align: right;
            margin-bottom: -5px;
        }
        .records .more a{
            display: inline-block;
            padding: 2px 15px;
            background: cadetblue;
            text-decoration: none;
            font-weight: bold;
            color: aliceblue;
        }

    </style>
</head>
<body>
    <div class="page">
        <div class="header"><h1>Blog</h1></div>
        <div class="menu">
            <?php foreach($menu as $item): ?>
                <p>
                    <a href="/index.php?rec=<?= $item['link']?>"><?= $item['title']?></a>
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
