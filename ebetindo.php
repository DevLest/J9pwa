
<?php
    $post = file_get_contents('php://input'); 

    $obj = (object) json_decode($post);

    print_r("$post\n");
    print_r($obj);
    print_r($_POST);
    print_r($_SERVER["REQUEST_METHOD"]);
    print_r($_SERVER);

    $data = [
        'status' => '200',
        'subChannelId' => '0',
        'username' => $obj->username,
        'accessToken' => $obj->accessToken,
        'currency'=> "IDR",
    ];

    header('Content-type: application/json');
    echo json_encode($data);