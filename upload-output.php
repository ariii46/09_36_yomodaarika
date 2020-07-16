<?php
	session_start(); // セッションの開始
	include('functions.php'); // 関数ファイル読み込み
	//check_session_id(); // idチェック関数の実行
?>
<?php
	// 送信確認
	//var_dump($_POST);var_dump($_FILES);exit();

	//ユーザー情報取得（ssnは_SESSIONの意）
	$user_id=$_POST['txt_user_id'];//$_SESSION['user_id'];//ユーザーid

	// 項目入力のチェック
	// 値が存在しないor空で送信されてきた場合はNGにする
	if(!isset($_POST['txt_speech']) || $_POST['txt_speech']==''){// 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
		echo json_encode(["error_msg" => "no input"]);
		exit();
	}

	$fn='';//保存写真ファイル名
	if(isset($_FILES)&& isset($_FILES['fle_photo']) && is_uploaded_file($_FILES['fle_photo']['tmp_name'])){
		if(!file_exists('upload')){
			mkdir('upload');
		}
		$phototype=$_FILES['fle_photo']['type'];//画像タイプ（「image/jpeg」を想定）
		if($phototype=='image/jpeg'){//ジェペグなら
			$ext='jpg';//「jpg」に統一
			$fn=date('Ymd-His-').$user_id.'.'.$ext;//保存名「年月日-時分秒-ユーザーid」形式
			$dir='upload/';
			$path_save=$dir.$fn;
			if(move_uploaded_file($_FILES['fle_photo']['tmp_name'],$path_save)){
				list($width,$hight)=getimagesize($path_save);// 元の画像名を指定してサイズを取得
				$im=imagecreatefromjpeg($path_save);// 元の画像から新しい画像を作る準備
				
				$asc_set=array('tn'=>50,'ex'=>500);//①サムネイル用(50x50) ②拡大用写真を生成
				foreach($asc_set as $pre=>$max_px){
					$ratio=1;//縮小率
					if($width>$hight){
						$ratio=$max_px/$width;
					}else{
						$ratio=$max_px/$hight;
					}
					$new_width=$width*$ratio;
					$new_hight=$hight*$ratio;
					$image=imagecreatetruecolor($width*$ratio,$hight*$ratio); // サイズを指定して新しい画像のキャンバスを作成
					// 画像のコピーと伸縮
					imagecopyresampled($image,$im,0,0,0,0,$new_width,$new_hight,$width,$hight);
					// コピーした画像を出力する
					imagejpeg($image,$dir.$pre.$fn);//拡大用
				}
				unlink($path_save);//元ファイルを削除
			}
		}else{
			//NOP:アップロード写真なし
		}
	}

	// 受け取ったデータを変数に入れる
	$txt_speech=$_POST['txt_speech'];
	//$fle_photo=$_POST['fle_photo'];

	// DB接続
	$pdo=connect_to_db();

	// データ登録SQL作成
	// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
	$sql='INSERT INTO speech_table(sn,user_id,speech_content, photo_fn,speech_at) VALUES(NULL,:user_id,:speech_content,:photo_fn,sysdate())';

	// SQL準備&実行
	$stmt=$pdo->prepare($sql);
	$stmt->bindValue(':user_id',$user_id,PDO::PARAM_STR);
	$stmt->bindValue(':speech_content',$txt_speech,PDO::PARAM_STR);
	$stmt->bindValue(':photo_fn',$fn,PDO::PARAM_STR);
	$status=$stmt->execute();

	// データ登録処理後
	if($status==false){
		// SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
		$error=$stmt->errorInfo();
		echo json_encode(["error_msg" => "{$error[2]}"]);
		exit();
	} else {
		// 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
		header("Location:timeline.php");
		exit();
	}
