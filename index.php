<?php

//共通関数の読込 データベース接続
//セッション開始
session_start();
// var_dump($_SESSION['101']);
// exit();

//ログイン情報を取得
// $user_id = $_SESSION['user_id'];
// var_dump($user_id);
// exit();

// 外部ファイル読込
include('functions.php');

// ログイン状態をチェック idチェック関数実行
// check_session_id();

//DB接続
$pdo = connect_to_db();
// データ取得SQL作成
//ユーザー情報と投稿内容を紐付
$sql = "SELECT user_id, speech_content, photo_fn, speech_at FROM speech_table ORDER BY speech_at DESC";

// ユーザー別の投稿数を集計
// SELECT 集計するキーとなるカラム名, COUNT(列名) AS 任意のカラム名を作成 FROM テーブル名 GROUP BY 集計するキーとなるカラム名
// posts_table の user_id ごとに id数 を「cnt」というカラム名で表示
// $sql = "SELECT user_id, COUNT(id) AS cnt FROM posts_table GROUP BY user_id";
// テーブルを外部結合
// $sql = 'SELECT * FROM users_table LEFT OUTER JOIN (SELECT user_id, COUNT(id) AS cnt FROM posts_table GROUP BY user_id) AS posts
// ON users_table.id = posts.user_id';


// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();



// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定

}

// $valueの参照を解除する．解除しないと再度foreachした場合に最初からループしない
unset($record);

?>



<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v4.0.1">
  <title>SNS example · Bootstrap</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/album/">

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.css" rel="stylesheet">

  <!-- 追加 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <!-- 追加 -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

  <!-- 追加 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX96tW9Wr5hrh3pZLMBbdoxvWy8G4DIto&callback=initMap&libraries=places" async defer></script>

  <!-- okioka add start -->
  <script src="js/login_status_a.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.8.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.8.1/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/ui/3.5.2/firebase-ui-auth__ja.js"></script>
  <script src="js/config.js"></script>

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <!-- Custom styles for this template -->
  <link href="css/album.css" rel="stylesheet">




  <!-- 地図のサイズ -->
  <style>
    #target {
      width: 0 auto;
      height: 500px;
    }
  </style>

</head>


<body>
  <!-- SNSヘッダー画面 -->
  <header>

    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container d-flex justify-content-between">


        <!-- ログイン画面の処理 -->

        <div class="container">
          <p>
            <a href="login.php" class="btn btn-primary my-2">ログイン</a>
            <a href="login.php" class="btn btn-secondary my-2">ログアウト</a>
          </p>

          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false">
              <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
              <circle cx="12" cy="13" r="4" /></svg>
            <!-- <strong>SNS</strong> -->
          </a>

          <!-- okioka start -->
          <!-- <a href="#" class="navbar-brand d-flex align-items-center">マップ</a>
          <a href="timeline.php" class="navbar-brand d-flex align-items-center">タイムライン</a>
          <a href="#" class="navbar-brand d-flex align-items-center">イベント</a> -->

          <input class="menu_btn" type="button" value="マップ" onClick="login_status()" />
          <form action="timeline.php" method="POST">
            <fieldset>
              <div>
                <input type="hidden" id="user_id" name="my_user_id">
                <button class="menu_btn" id="get_user_id">タイムライン</button>
              </div>
            </fieldset>
          </form>
          <input class="menu_btn" type="button" value="イベント" onClick="login_status()" />

          <!-- <input type="button" value="テスト" onClick="login_status()" /> -->

          <style>
            /* ここから下がボタンのCSS　*/
            .menu_btn {
              background-color: transparent;
              border: none;
              cursor: pointer;
              outline: none;
              padding: 0;
              appearance: none;
              color: antiquewhite;
              font-size: 20px;
              font-weight: bolder;
            }

            .menu_btn:hover {
              opacity: 0.8;
            }
          </style>


          <script>
            $(function() {
              $('#get_user_id').on('click', function() {
                // alert(login_status())
                $("#user_id").val(login_status());
              });
            });
          </script>
          <!-- okioka end -->

          <!-- <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator,
          etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p> -->
        </div>
      </div>
    </div>
  </header>


  <!-- マップを表示するAPIの処理 -->
  <div id="target"></div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- axiosライブラリの読み込み -->
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX96tW9Wr5hrh3pZLMBbdoxvWy8G4DIto&callback=initMap&libraries=places" async defer></script>

  <script>
    function initMap() {

      // Geolocation
      if (!navigator.geolocation) {
        alert('Geolocation not supported');
        return;
      }

      let target = document.getElementById("target");

      let map;
      let marker;

      navigator.geolocation.getCurrentPosition(function(position) {

        // 現在地を表示する
        map = new google.maps.Map(target, {
          center: {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          },
          zoom: 14
        });

        // マップにマーカーを表示する
        let mapLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        marker = new google.maps.Marker({
          map: map,
          position: mapLatLng,
          animation: google.maps.Animation.BOUNCE
          // animation: google.maps.Animation.DROP
        });

        // 検索ボタン
        document.getElementById('search').addEventListener('click', function() {

          let service;
          service = new google.maps.places.PlacesService(map);

          let myposilat = position.coords.latitude
          let myposilng = position.coords.longitude

          // coords.latitude	現在位置の緯度。-180〜180で表す。
          // coords.longitude	現在位置の経度。-90〜90で表す。
          // coords.altitude	現在位置の高度。メートル単位で表す。

          service.nearbySearch({
            location: {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            },
            radius: '500',
            name: document.getElementById('keyword').value
          }, function(results, status) {

            let i;

            if (status === 'OK') {

              let arr = [];
              let outputArray = [];
              arr.length = results.length;

              for (i = 0; i < results.length; i++) {

                new google.maps.Marker({
                  map: map,
                  position: results[i].geometry.location,
                  title: results[i].name
                })

                let markposilat = results[i].geometry.location.lat();
                let markposilng = results[i].geometry.location.lng();

                // console.log(results[i]);

                outputArray.push(`<p>${results[i].name} [現在地からの距離：${distance(myposilat, myposilng, markposilat, markposilng)} m ] </p>`);
                $('#output').html(outputArray);
              }
            } else {
              alert('Failed:' + status);
              return;
            }
          });
        });
      }, function() {
        alert('Geolocation failed');
        return;
      });

      function distance(lat1, lng1, lat2, lng2) {
        lat1 *= Math.PI / 180;
        lng1 *= Math.PI / 180;
        lat2 *= Math.PI / 180;
        lng2 *= Math.PI / 180;
        // km → m
        // 小数点２桁目切り捨て
        return Math.floor((6371 * Math.acos(Math.cos(lat1) * Math.cos(lat2) * Math.cos(lng2 - lng1) + Math.sin(lat1) * Math.sin(lat2)) * 1000) * 100) / 100;
      }
    }
  </script>


  <main role="main">
    <!-- <div id="target"></div> -->

    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row">
          <?php foreach ($result as $record) : ?>
            <div class="col-md-4">
              <div class="card mb-4">
                <div class="img_area bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail">
                  <img src="upload/ex<?= $record['photo_fn'] ?>" width="100%">
                </div>
                <div class="card-body">
                  <h2 class="card-title"><?= $record['user_id'] ?></h2>
                  <p class="card-text"><?= $record['speech_content'] ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group edit_btn_area">
                      <a href="post_edit.php" class="btn btn-sm">
                        <span class='material-icons md-18'>❤︎</span>
                      </a>
                      <a href="post_delete.php" class="btn btn-sm">
                        <span class='material-icons md-18'></span>
                      </a>
                    </div>
                    <small class="text-muted"><?= $record['speech_at'] ?></small>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
    <!-- 違いを見つける -->
    <!-- <div class="album py-5 bg-light">
      <div class="container">
        <div class="row"> -->
    <!-- <div class="col-md-4"> -->
    <!-- <div class="card mb-4 shadow-sm"> -->
    <!-- <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"> -->
    <!-- <title>Placeholder</title> -->
    <!-- <rect width="100%" height="100%" fill="#55595c" /><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text> -->
    <!-- </svg> -->
    <!-- <div class="card-body"> -->
    <!-- <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional
                  content. This content is a little bit longer.</p> -->
    <!-- <div class="d-flex justify-content-between align-items-center"> -->
    <!-- <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                  </div> -->
    <!-- <small class="text-muted">9 mins</small> -->
    <!-- </div>
              </div>
            </div>
          </div> -->
  </main>

  <footer class="text-muted">
    <div class="container">
      <p class="float-right">
        <a href="#">Back to top</a>
      </p>
      <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
      <p>New to Bootstrap? <a href="https://getbootstrap.com/">Visit the homepage</a> or read our <a href="../getting-started/introduction/">getting started guide</a>.</p>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script>
    window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')
  </script>
  <script src="../assets/dist/js/bootstrap.bundle.js"></script>
</body>

</html>