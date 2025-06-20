
// ================================
// PROTEÇÃO + RENDER FAVORITOS FIRESTORE
// ================================
firebase.auth().onAuthStateChanged(user => {
  if (!user) {
    alert("Você precisa estar logado para acessar seus favoritos.");
    window.location.href = "/HTML/login.html";
  } else {
    renderizarFavoritos(user.uid);
  }
});

function renderizarFavoritos(userId) {
  const cont = document.getElementById("favoritos-container");
  const msg  = document.getElementById("msg-vazio");

  db.collection("usuarios").doc(userId)
    .collection("favoritos")
    .get()
    .then(snapshot => {
      cont.innerHTML = "";
      if (snapshot.empty) {
        msg.style.display = "block";
        return;
      }
      msg.style.display = "none";

      snapshot.forEach(doc => {
        const p = doc.data();
        const precoOld = p.precoAntigo
          ? `<span class="preco-antigo">R$ ${p.precoAntigo.toFixed(2).replace(".",",")}</span>`
          : "";
        const div = document.createElement("div");
        div.className = "produto";
        div.innerHTML = `
          <img src="${p.imagem}" alt="${p.nome}" />
          <h3>${p.nome}</h3>
          <div class="produto-preco">${precoOld} R$ ${p.preco.toFixed(2).replace(".",",")}</div>
          <button class="btn-remover-favorito">Remover</button>
        `;
        div.querySelector(".btn-remover-favorito").addEventListener("click", () => {
          db.collection("usuarios").doc(userId)
            .collection("favoritos")
            .doc(String(p.id))
            .delete()
            .then(() => renderizarFavoritos(userId));
        });
        cont.appendChild(div);
      });
    })
    .catch(err => console.error("Erro ao buscar favoritos:", err));
}


// Seletores
const favoritosContainer = document.getElementById("favoritos-container");
const msgVazio = document.getElementById("msg-vazio");

// Carrega favoritos do localStorage
function carregarFavoritos() {
  const favoritosJSON = localStorage.getItem("favoritos");
  if (!favoritosJSON) {
    mostrarMensagemVazio(true);
    return [];
  }

  const favoritos = JSON.parse(favoritosJSON);
  if (favoritos.length === 0) {
    mostrarMensagemVazio(true);
    return [];
  }

  mostrarMensagemVazio(false);
  return favoritos;
}

// Mostra ou esconde a mensagem de vazio
function mostrarMensagemVazio(mostrar) {
  msgVazio.style.display = mostrar ? "block" : "none";
  favoritosContainer.style.display = mostrar ? "none" : "grid";
}

// Renderiza os produtos favoritos
function renderizarFavoritos() {
  const favoritos = carregarFavoritos();
  favoritosContainer.innerHTML = "";

  favoritos.forEach((produto) => {
    const precoAntigoHtml = produto.precoAntigo
      ? `<span class="preco-antigo">R$ ${produto.precoAntigo.toFixed(2).replace(".", ",")}</span>`
      : "";

    const divProduto = document.createElement("div");
    divProduto.classList.add("produto");
    divProduto.innerHTML = `
      <img src="${produto.imagem}" alt="Imagem do produto ${produto.nome}" />
      <h3>${produto.nome}</h3>
      <div class="produto-preco">${precoAntigoHtml} R$ ${produto.preco.toFixed(2).replace(".", ",")}</div>
      <button class="btn-remover-favorito" aria-label="Remover ${produto.nome} dos favoritos">Remover</button>
    `;

    // Remove item ao clicar em "Remover"
    divProduto.querySelector(".btn-remover-favorito").addEventListener("click", () => {
      removerFavorito(produto.id);
    });

    favoritosContainer.appendChild(divProduto);
  });
}

// Remove o produto do localStorage
function removerFavorito(id) {
  const favoritosJSON = localStorage.getItem("favoritos");
  if (!favoritosJSON) return;

  let favoritos = JSON.parse(favoritosJSON);
  favoritos = favoritos.filter((p) => p.id !== id);
  localStorage.setItem("favoritos", JSON.stringify(favoritos));
  renderizarFavoritos();
}
 // ================================
// FAVORITOS FIRESTORE (COM PROTEÇÃO DE LOGIN)
// ================================
firebase.auth().onAuthStateChanged(user => {
  if (!user) {
    alert("Você precisa estar logado para acessar seus favoritos.");
    window.location.href = "/HTML/login.html";
  } else {
    renderizarFavoritos(user.uid);
  }
});

function renderizarFavoritos(userId) {
  const favoritosContainer = document.getElementById("favoritos-container");
  const msgVazio = document.getElementById("msg-vazio");

  firebase.firestore()
    .collection("usuarios")
    .doc(userId)
    .collection("favoritos")
    .get()
    .then(snapshot => {
      favoritosContainer.innerHTML = "";
      if (snapshot.empty) {
        msgVazio.style.display = "block";
        return;
      }

      msgVazio.style.display = "none";

      snapshot.forEach(doc => {
        const produto = doc.data();
        const precoAntigoHtml = produto.precoAntigo
          ? `<span class="preco-antigo">R$ ${produto.precoAntigo.toFixed(2).replace(".", ",")}</span>`
          : "";

        const divProduto = document.createElement("div");
        divProduto.classList.add("produto");
        divProduto.innerHTML = `
          <img src="${produto.imagem}" alt="Imagem do produto ${produto.nome}" />
          <h3>${produto.nome}</h3>
          <div class="produto-preco">${precoAntigoHtml} R$ ${produto.preco.toFixed(2).replace(".", ",")}</div>
          <button class="btn-remover-favorito">Remover</button>
        `;

        divProduto.querySelector(".btn-remover-favorito").addEventListener("click", () => {
          firebase.firestore()
            .collection("usuarios")
            .doc(userId)
            .collection("favoritos")
            .doc(String(produto.id))
            .delete()
            .then(() => renderizarFavoritos(userId));
        });

        favoritosContainer.appendChild(divProduto);
      });
    })
    .catch(error => {
      console.error("Erro ao buscar favoritos:", error);
    });
}