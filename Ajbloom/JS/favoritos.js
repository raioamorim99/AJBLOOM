// Recupera favoritos do localStorage
const favoritosContainer = document.getElementById("favoritos-container");
const msgVazio = document.getElementById("msg-vazio");

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

function mostrarMensagemVazio(mostrar) {
  msgVazio.style.display = mostrar ? "block" : "none";
  favoritosContainer.style.display = mostrar ? "none" : "grid";
}

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

    // Evento para remover favorito
    divProduto.querySelector(".btn-remover-favorito").addEventListener("click", () => {
      removerFavorito(produto.id);
    });

    favoritosContainer.appendChild(divProduto);
  });
}

function removerFavorito(id) {
  const favoritosJSON = localStorage.getItem("favoritos");
  if (!favoritosJSON) return;

  let favoritos = JSON.parse(favoritosJSON);
  favoritos = favoritos.filter((p) => p.id !== id);
  localStorage.setItem("favoritos", JSON.stringify(favoritos));
  renderizarFavoritos();
}

renderizarFavoritos();