// === 1. CONFIGURAÇÃO DO FIREBASE ===
const firebaseConfig = {
  apiKey: "AIzaSyDV1x2NIvYm6z7BmxQNDbHCYwxYyR5OMMQ",
  authDomain: "ajbloom-login.firebaseapp.com",
  projectId: "ajbloom-login",
  storageBucket: "ajbloom-login.appspot.com",
  messagingSenderId: "99320025218",
  appId: "1:99320025218:web:616cd54aff52671e9da49b"
};

firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();

// === 2. ELEMENTOS DO DOM ===
const inputEmail       = document.getElementById("email");
const inputSenha       = document.getElementById("senha");
const btnLogin         = document.getElementById("btn-login");
const btnCadastrar     = document.getElementById("btn-cadastrar");
const btnGoogle        = document.getElementById("btn-google");
const btnLogout        = document.getElementById("btn-logout");
const usuarioLogadoSpan= document.getElementById("usuario-logado");

// === 3. STATUS DE AUTENTICAÇÃO ===
auth.onAuthStateChanged(user => {
  if (user) {
    if (window.location.pathname.endsWith("login.html")) {
      window.location.href = "favoritos.html";
    }

    if (usuarioLogadoSpan && btnLogout) {
      usuarioLogadoSpan.textContent = `Olá, ${user.email}`;
      btnLogout.style.display = "inline-block";
    }
  } else {
    if (window.location.pathname.endsWith("favoritos.html")) {
      window.location.href = "login.html";
    }

    if (usuarioLogadoSpan && btnLogout) {
      usuarioLogadoSpan.textContent = "";
      btnLogout.style.display = "none";
    }
  }
});

// === 4. LOGIN COM EMAIL/SENHA ===
if (btnLogin) {
  btnLogin.addEventListener("click", () => {
    auth.signInWithEmailAndPassword(inputEmail.value, inputSenha.value)
      .then(() => window.location.href = "favoritos.html")
      .catch(err => alert(err.message));
  });
}

// === 5. CADASTRO COM EMAIL/SENHA ===
if (btnCadastrar) {
  btnCadastrar.addEventListener("click", () => {
    auth.createUserWithEmailAndPassword(inputEmail.value, inputSenha.value)
      .then(() => {
        alert("Conta criada com sucesso!");
        window.location.href = "favoritos.html";
      })
      .catch(err => alert(err.message));
  });
}

// === 6. LOGIN COM GOOGLE ===
if (btnGoogle) {
  btnGoogle.addEventListener("click", () => {
    const provider = new firebase.auth.GoogleAuthProvider();
    auth.signInWithPopup(provider)
      .then(() => window.location.href = "favoritos.html")
      .catch(err => alert("Erro no login com Google: " + err.message));
  });
}

// === 7. LOGOUT ===
if (btnLogout) {
  btnLogout.addEventListener("click", () => {
    auth.signOut()
      .then(() => {
        alert("Você saiu da conta.");
        window.location.href = "login.html";
      })
      .catch(err => alert("Erro ao sair: " + err.message));
  });
}
auth.onAuthStateChanged(user => {
  console.log("🔥 user logado?", user);

});
