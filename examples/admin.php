<?php

include 'bootstrap.php';


$db = new \db\SPDO(DB_HOST);

$tableExist = $db->tableInfo('records');
if(!$tableExist)
    die('Table "records" not exists!');


$menu = $db->select('id, link, title', 'records','active=?', 1);

class API {
    private $contentType;
    private $registered = [];
    function __construct() {}
    function start() {
        $this->contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($this->contentType, "application/json") !== false) {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);
            if(is_array($decoded) && isset($decoded['key']) && isset($this->registered[$decoded['key']])) {
                $callback = $this->registered[$decoded['key']];
                unset($decoded['key']);
                call_user_func_array($callback, $decoded);
                exit;
            }
        }
    }
    function register($key, $callback) {
        $this->registered[$key] = $callback;
    }
    function json($data) {
        echo json_encode($data);
    }
}

$api = new API();

$api->register('save', function ($id, $title, $description) use ($api, $db) {
    $result = null;
    $key = null;
    $link = null;
    if (empty($id)) {
        $key = 'insert';
        $link = strtolower(substr(md5($title), 0, 10));
        $result = $db->insert('records', [
            'title' => $title,
            'description' => $description,
            'link' => strtolower(substr(md5($title), 0, 10)),
            'image' => 'none',
            'iduser' => 1,
            'active' => 1,
        ]);
    } else {
        $key = 'update';
        $result = $db->update('records', [
            'title' => $title,
            'description' => $description,
        ], 'id=?', [$id]);
        if ($result)
            $link = $db->select('link', 'records','id=?', $id)[0]['link'];
    }
    $api->json([
        'key' => $key,
        'link' => $link,
        'result' => $result
    ]);
});

$api->register('remove', function ($id) use ($api, $db) {
    $result = null;
    if (!empty($id))
        $result = $db->delete('records', 'id=?', [$id]);
    $api->json([
        'key' => 'remove',
        'result' => $result
    ]);
});

$api->start();


$contentBlog = false;
$contentEdit = false;
if(isset($_GET['edit']) && ($link = $_GET['edit'])){
    $sql = "SELECT r.id, r.title, r.description, u.name, u.email
            FROM records r LEFT JOIN users u ON (u.id = r.iduser) WHERE r.link=:link";
    $stat = $db->prepare($sql);
    $stat->bindParam(':link',$link,PDO::PARAM_STR);
    $stat->execute();
    $contentEdit = $stat->fetch();

} else {
    $contentEdit = [
            'title'=> null,
            'description'=> null,
            'name'=> null,
            'email'=> null,
        ];
    // header('Location: /index.php');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .content {}
        .content input, .content textarea {
            border: none;
            border-bottom: 2px solid #236264;
        }
        .content input:focus, .content textarea:focus {
            outline: none;
            border-bottom: 2px solid #01bdff;
        }
        .content input[data-name="title"] {
            width: 100%;
            font-size: 18px;
        }
        .content textarea {
            width: 100%;
            height: 100px;
        }
    </style>
    <script>
        const url = './admin.php';

         function onSave () {
            let data = {
                key: 'save',
                id: document.querySelector('[data-name="id"]').value,
                title: document.querySelector('[data-name="title"]').value,
                description: document.querySelector('[data-name="description"]').value,
            };

             HTTPRequest (data, function (response) {
                 if (response && response.result) {
                     location.href = './index.php?rec=' + response.link;
                 }
            })
        }

        function onRemove () {
            let data = {
                key: 'remove',
                id: document.querySelector('[data-name="id"]').value,
            };
            if (data.id > 0)
                HTTPRequest(data, function (response) {
                    if (response && response.result)
                        location.href = './index.php';
                })
        }

        async function HTTPRequest(data, callback) {
            let response = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8; '},
                body: JSON.stringify(data)
            });

            if (response.ok) {
                try {
                    let json = await response.json();
                    callback.call(response, json);
                } catch (e) {
                    console.log('JSON decode is failed. Response.text: ', await response.text());
                }
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }
    </script>
</head>
<body>
    <div class="page">
        <div class="header"><h1>DB Application</h1></div>
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
            <?php if($contentEdit):?>

            <div class="record">
                <div class="back"><a href="/index.php">Back</a></div>
                <h2>
                    <input type="text" data-name="title" value="<?= $contentEdit['title']?>">
                    <input type="text" data-name="id" hidden value="<?= isset($contentEdit['id'])?$contentEdit['id']:null?>">
                </h2>
                <div class="desc">
                    <textarea data-name="description"><?= $contentEdit['description']?></textarea>
                </div>
                <div class="author">
                    Author: <input type="text" disabled data-name="name" value="<?= $contentEdit['name']?>">
                    |
                    Email: <input type="text" disabled data-name="email" value="<?= $contentEdit['email']?>">
                </div>
                <div class="action">
                    <div class="button" onclick="onSave()">Save</div>
                    <div class="button" onclick="onRemove()">Remove</div>
                </div>
            </div>

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
