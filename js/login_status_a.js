//----------------------------------------------
// ログイン状態のチェック
//----------------------------------------------
function login_status() {

  firebase.auth().onAuthStateChanged((user) => {

    // ログイン済
    if (user) {
      // userid = user.displayName;
      userid = user.uid;
      // alert(userid);
      // return userid;
    }
    // 未ログイン
    else {
      // // userid = "";
      userid = "";
      // alert(userid);
      // return userid;
    }
  });
  // alert(userid);
  return userid;
};