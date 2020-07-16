<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>ログイン認証</title>
  <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.css" />
  <style type="text/css">
    h1 {
      text-align: center;
    }

    #info {
      text-align: center;
    }

    #logout {
      text-align: center;
    }

    .hide {
      display: none;
    }
  </style>
</head>

<body>
  <h1>ログイン認証</h1>
  <div id="info"></div>
  <div id="firebaseui-auth-container"></div>
  <div id="logout" class="hide">
    <form><button type="button" id="btn-logout">ログアウト</button></form>
  </div>

  <script src="https://www.gstatic.com/firebasejs/5.8.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.8.1/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/ui/3.5.2/firebase-ui-auth__ja.js"></script>
  <script src="js/config.js"></script>
  <script>
    //----------------------------------------------
    // Firebase UIの設定
    //----------------------------------------------
    var uiConfig = {

      // ログイン完了時のリダイレクト先
      // signInSuccessUrl: 'index.php?username=' + user.displayName + "&userid=" + user.uid,
      signInSuccessUrl: 'index.php',

      // 利用する認証機能
      signInOptions: [
        firebase.auth.GoogleAuthProvider.PROVIDER_ID,
        firebase.auth.FacebookAuthProvider.PROVIDER_ID,
        firebase.auth.TwitterAuthProvider.PROVIDER_ID,
        // firebase.auth.GithubAuthProvider.PROVIDER_ID,
        {
          provider: firebase.auth.PhoneAuthProvider.PROVIDER_ID,
          defaultCountry: 'JP'
        },
        firebase.auth.EmailAuthProvider.PROVIDER_ID,
        // guest
        // firebaseui.auth.AnonymousAuthProvider.PROVIDER_ID
      ],

      // 利用規約のURL(任意で設定)
      tosUrl: '	userpolicy.html',
      // プライバシーポリシーのURL(任意で設定)
      privacyPolicyUrl: 'privacy.html'
    };

    //----------------------------------------------
    // ログイン状態のチェック
    //----------------------------------------------
    firebase.auth().onAuthStateChanged((user) => {
      // ログイン済
      if (user) {
        showLogin('ログイン中', `${user.displayName}さんがログインしています<br>(${user.uid})`);
      }
      // 未ログイン
      else {
        var ui = new firebaseui.auth.AuthUI(firebase.auth());
        ui.start('#firebaseui-auth-container', uiConfig);
      }
    });

    //----------------------------------------------
    // ログアウト
    //----------------------------------------------
    document.querySelector('#logout').addEventListener("click", () => {
      firebase.auth().signOut().then(() => {
          showLogout("ログイン認証", "");
        })
        .catch((error) => {
          alert(`ログアウトできませんでした(${error})`);
        });
    });

    // ログイン時の各種表示
    function showLogin(title, msg) {
      document.querySelector('h1').innerHTML = title;
      document.querySelector('#info').innerHTML = msg;
      document.querySelector('#logout').classList.remove("hide");
    }

    // ログアウト時の各種表示
    function showLogout(title, msg) {
      document.querySelector('h1').innerHTML = title;
      document.querySelector('#info').innerHTML = msg;
      document.querySelector('#logout').classList.add("hide");
    }
  </script>
</body>

</html>