// Verifica se o botão logout e span existem na página
const btnLogout = document.getElementById("btn-logout");
const usuarioLogadoSpan = document.getElementById("usuario-logado");

if (btnLogout && usuarioLogadoSpan) {
  // Só executa esse código se os elementos existirem
  firebase.auth().onAuthStateChanged(user => {
    if (user) {
      usuarioLogadoSpan.textContent = `Olá, ${user.email}`;
      btnLogout.style.display = "inline-block";
    } else {
      usuarioLogadoSpan.textContent = "";
      btnLogout.style.display = "none";
    }
  });

  btnLogout.addEventListener("click", () => {
    firebase.auth().signOut()
      .then(() => {
        alert("Você saiu da conta.");
        window.location.href = "login.html";
      })
      .catch((error) => {
        alert("Erro ao sair: " + error.message);
      });
  });
}

// Login Google (esse código fica sempre, pois o botão existe no login.html)
const btnGoogle = document.getElementById("btn-google");

if (btnGoogle) {
  btnGoogle.addEventListener("click", () => {
    const provider = new firebase.auth.GoogleAuthProvider();
    firebase.auth()
      .signInWithPopup(provider)
      .then((result) => {
        alert(`Bem-vindo(a), ${result.user.displayName || result.user.email}!`);
        window.location.href = "index.html"; // redireciona para home
      })
      .catch((error) => {
        alert("Erro no login com Google: " + error.message);
      });
  });
}