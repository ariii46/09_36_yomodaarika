<?php

// okioka start
$my_user_id = $_POST["my_user_id"];
// $my_user_id = '101';

// var_dump($my_user_id);
// exit();
// okioka end

session_start(); // セッションの開始
include('functions.php');
$pdo = connect_to_db();



// 全部のカラムを取ってくる
$sql = 'SELECT * FROM speech_table ORDER BY speech_at DESC';
// よくわかんないやつ SQLの準備
$stmt = $pdo->prepare($sql);
// sqlのじっこう
$status = $stmt->execute();


// 失敗時にエラーを出力
if ($status == false) {
    $error = $stmt->errorInfo();
    exit('sqlError:' . $error[2]);
} else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "";
}

foreach ($result as $record) {
    $photo_fn = $record["photo_fn"];
    $user_id = $record["user_id"];

    $output .= "<div class='speech_" . ($user_id == $my_user_id ? 'self' : 'other') . "'>";
    // var_dump($output);
    // exit();
    if ($photo_fn != '') {
        $output .= "<div>";
        $output .= "<img class='img_tn' src = 'upload/tn{$photo_fn}'>";
        $output .= "</div>";
    }
    $output .= "<div>";
    $output .= "{$record["user_id"]}";
    $output .= "{$record["speech_at"]}<br>";
    $output .= "{$record["speech_content"]}";
    // $output .= "{$record[""]}";
    $output .= "</div>";
    $output .= "</div>";
}
?>

<style>
    body {
        font-family: "M PLUS Rounded 1c";
        padding-top: 60px;
        color: #696969;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        list-style: none;
    }

    .pc_header {
        width: 100%;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        /* background: #fff; */
        position: fixed;
        top: 0;
        z-index: 5;
        /* border-bottom: solid 2px #1739b7; */
        background-color: #5EC093;
    }

    .pc_header .logo {
        line-height: 0;
    }

    .pc_header .logo img {
        height: 30px;
    }

    .pc_header ul {
        display: flex;
    }

    .pc_header ul li a {
        display: block;
        color: #000;
        text-decoration: none;
        padding: 0.2em 0.5em;
        font-size: 15px;
    }


    .display {
        max-width: 500px;
        margin: auto;
        padding: 10px;
    }

    .mainvisual {
        width: 100%;
        height: 300px;
        background: #aaa;
        background-image: url(img/main.jpg);
        background-size: cover;
        background-position: center;
        padding: 60px;
    }

    .mainvisual .inner {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.5);
    }


    .mainvisual .logo {
        width: 80%;
    }

    .mainvisual .main_copy {
        font-size: 80px;
        color: #696969;
        font-weight: bold;
    }

    .submit_text {
        text-align: center;
        padding-top: 15px;
        padding-bottom: 25px;
    }

    .question_box {
        padding: 15px;
    }

    .speech_self {
        background-color: #CEDCD9;
        border: solid 1px #aaa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
    }



    .speech_other {
        border: solid 1px #aaa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
    }

    img {
        max-width: 100%;
        vertical-align: middle;
    }


    /* ここから下がボタンのCSS　*/
    .btn {
        text-align: center;
        font-size: 15px;
        display: inline-block;
        padding: 0.5em 2em;
        line-height: 1;
        border-radius: 0.3em;
        color: #fff;
        text-decoration: none;

        box-shadow: 2px 2px 10px -5px #064405;
        border: solid 1px #199217;
        background: linear-gradient(#6bd867, #24a724);
        transition: 0.3s;

        margin-top: 15px;
    }



    .btn:hover {
        box-shadow: none;
        background: linear-gradient(#49bd45, #1f8e1c);
    }
</style>




<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>タイトル</title>
    <meta name="description" content="ディスクリプション">

    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
</head>


<body>
    <div id='div_grayback' style="background-color:silver;opacity:0.8;display: none;position: absolute;left: 0;top: 0;"></div>
    <div id='div_photo'></div>
    <!-- ヘッダー -->
    <header class="pc_header">
        <div class="logo"><img src="img/account.jpg" alt="ロゴ"></div>
        <ul>
            <li><a href="#">タイムライン</a></li>
            <li><a href="index.php">マップ</a></li>
            <li><a href="#">イベント</a></li>
            <li><a href="#">お問い合わせ</a></li>
        </ul>
    </header>

    <!-- メインビジュアル -->

    <div class="mainvisual">
        <div class="inner">
            <div class="main_copy">ゴミ拾い 秀丸</div>
        </div>
    </div>

    <div class="submit">
        <form action="upload-output.php" method="post" enctype="multipart/form-data">
            <div class="submit_text">
                <p>【投稿】</p>
                <div class="question_box">
                    <textarea name="txt_speech" id="" cols="80" rows="3"></textarea>
                </div>
                <div>
                    <input type="file" name="fle_photo">
                    <input type='hidden' name='txt_user_id' value='<?= $my_user_id ?>'><br>
                    <button class="btn">投稿</button>
                </div>
        </form>
    </div>
    <div class="display">
        <p>【タイムライン】</p>
        <?= $output ?>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="js/style.js"></script> -->

    <script>
        $(document).on('click', '.img_tn', function(e) {
            alert('OK');
            src = $(this).attr('src');
            x = 100;
            y = 100;​
            $('#div_grayback').css('width', $(window).width()).css('height', $(window).height()).css('display', 'block');​
            src = src.replace('tn', 'ex');
            $('#div_photo').html('<img src="' + src + '">');
            $('#div_photo').css('display', 'block').css('position', 'fixed').css('top', x).css('left', y);​
        });


        $('#div_grayback').click(function() {
            $('#div_grayback').css('display', 'none');
            $('#div_photo').css('display', 'none');
        });​
        $('#div_photo').click(function() {
            $('#div_grayback').css('display', 'none');
            $('#div_photo').css('display', 'none');
        });
    </script>



</body>

</html>