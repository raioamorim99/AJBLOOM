const menuToggle = document.getElementById("menu-toggle");
const sideMenu = document.getElementById("side-menu");
const overlay = document.getElementById("overlay");
const closeMenuBtn = document.getElementById("close-menu");

function abrirMenu() {
  sideMenu.classList.add("open");
  menuToggle.classList.add("open");
  overlay.classList.add("active");
  sideMenu.setAttribute("aria-hidden", "false");
  menuToggle.setAttribute("aria-expanded", "true");
  sideMenu.focus();
}

function fecharMenu() {
  sideMenu.classList.remove("open");
  menuToggle.classList.remove("open");
  overlay.classList.remove("active");
  sideMenu.setAttribute("aria-hidden", "true");
  menuToggle.setAttribute("aria-expanded", "false");
  menuToggle.focus();
}

menuToggle.addEventListener("click", () => {
  if (sideMenu.classList.contains("open")) {
    fecharMenu();
  } else {
    abrirMenu();
  }
});

closeMenuBtn.addEventListener("click", fecharMenu);
overlay.addEventListener("click", fecharMenu);

sideMenu.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", fecharMenu);
});

// ================================
// LISTA DE PRODUTOS (SIMULADOS)
// ================================
const produtos = [
  {
    id: 1,
    nome: "Camiseta Floral",
    preco: 79.9,
    precoAntigo: 99.9,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 2,
    nome: "Vestido Casual",
    preco: 129.9,
    precoAntigo: null,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8dmVzdGlkb3xlbnwwfHwwfHx8MA%3D%3D",
  },
  {
    id: 3,
    nome: "Brincos Delicados",
    preco: 49.9,
    precoAntigo: 59.9,
    categoria: "acessorios",
    imagem:
      "https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 4,
    nome: "Camiseta Estampada",
    preco: 89.9,
    precoAntigo: 109.9,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1738651875561-2f81f962338c?q=80&w=928&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 5,
    nome: "Vestido Elegante",
    preco: 159.9,
    precoAntigo: 199.9,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1617790274211-cbe0e677b425?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 6,
    nome: "Colar Fashion",
    preco: 79.9,
    precoAntigo: null,
    categoria: "acessorios",
    imagem:
      "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 7,
    nome: "Crooped Básica",
    preco: 59.9,
    precoAntigo: null,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1622116756680-fee39c84f9bd?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 8,
    nome: "Listrada",
    preco: 39.9,
    precoAntigo: 49.9,
    categoria: "acessorios",
    imagem:
      "https://images.unsplash.com/photo-1712811593599-d95c6fc6dece?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
  {
    id: 9,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8c2hvcnRzJTIwamVhbnN8ZW58MHx8MHx8fDA%3D",
  },
  {
    id: 10,
    nome: "Conjunto",
    preco: 69.9,
    precoAntigo: 89.9,
    categoria: "camisetas",
    imagem:
      "https://plus.unsplash.com/premium_photo-1723553201287-ab9a8fbe57d1?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
  },
];

// ================================
// VARIÁVEIS DE ESTADO (FILTRO, BUSCA E PAGINAÇÃO)
// ================================
const produtosPorPagina = 6;
let paginaAtual = 1;
let categoriaSelecionada = "tudo";
let termoDeBusca = ""; // guarda o texto digitado

// ================================
// ELEMENTOS DO DOM
// ================================
const produtosContainer = document.getElementById("produtos");
const filtroCategoria = document.getElementById("filtro-categoria");
const botaoVerMais = document.getElementById("ver-mais");

// Captura todos os inputs de busca (mobile e desktop)
const searchInputs = document.querySelectorAll(".search-input");

// ================================
// FUNÇÃO: RENDERIZAR PRODUTOS (AGORA COM BUSCA)
// ================================
function renderizarProdutos() {
  produtosContainer.innerHTML = "";

  // 1) Filtrar por categoria
  let produtosFiltrados =
    categoriaSelecionada === "tudo"
      ? produtos.slice()
      : produtos.filter((p) => p.categoria === categoriaSelecionada);

  // 2) Filtrar por termo de busca
  if (termoDeBusca.trim() !== "") {
    produtosFiltrados = produtosFiltrados.filter((p) =>
      p.nome.toLowerCase().includes(termoDeBusca.toLowerCase())
    );
  }

  // 3) Paginação: pega apenas os N primeiros
  const produtosVisiveis = produtosFiltrados.slice(
    0,
    paginaAtual * produtosPorPagina
  );

  // 4) Renderização final de cada produto
  produtosVisiveis.forEach((produto) => {
    const divProduto = document.createElement("div");
    divProduto.classList.add("produto");

    const precoAntigoHtml = produto.precoAntigo
      ? `<span class="preco-antigo">R$ ${produto.precoAntigo
          .toFixed(2)
          .replace(".", ",")}</span>`
      : "";

    divProduto.innerHTML = `
      <button class="btn-favoritar" aria-label="Favoritar produto ${produto.nome}">♡</button>
      <img src="${produto.imagem}" alt="Imagem do produto ${produto.nome}" />
      <div class="produto-info">
        <h3>${produto.nome}</h3>
        <div class="produto-preco">${precoAntigoHtml} R$ ${produto.preco
      .toFixed(2)
      .replace(".", ",")}</div>
      </div>
    `;

    produtosContainer.appendChild(divProduto);
  });

  // 5) Controla exibição do botão “Ver mais”
  botaoVerMais.style.display =
    produtosVisiveis.length >= produtosFiltrados.length ? "none" : "block";
}

// ================================
// EVENTOS: FILTRO, BUSCA E PAGINAÇÃO
// ================================

// Quando muda categoria
filtroCategoria.addEventListener("change", (e) => {
  categoriaSelecionada = e.target.value;
  paginaAtual = 1;

  // Limpa o campo de busca ao trocar a categoria
  termoDeBusca = "";
  searchInputs.forEach(input => input.value = "");

  renderizarProdutos();
});

// Quando clica “Ver mais”
botaoVerMais.addEventListener("click", () => {
  paginaAtual++;
  renderizarProdutos();
});

// ========================================================
// 1) DEBOUNCE SIMPLES NA BUSCA (200 ms)
// ========================================================
function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

// Handler que será associado aos inputs de busca
const handleSearchInput = debounce((event) => {
  termoDeBusca = event.target.value.trim();
  paginaAtual = 1;
  renderizarProdutos();
}, 200);

// Associando listeners de busca (mobile + desktop)
searchInputs.forEach((input) => {
  // Remove event listeners anteriores (se houver)
  input.removeEventListener("input", () => {});
  // Adiciona busca debounced
  input.addEventListener("input", handleSearchInput);

  // 2) Ao pressionar ENTER no campo, renderizar imediatamente e rolar até “destaques”
  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      termoDeBusca = e.target.value.trim();
      paginaAtual = 1;
      renderizarProdutos();

      // Scroll suave até a seção de produtos
      const produtosSection = document.getElementById("destaques");
      if (produtosSection) {
        produtosSection.scrollIntoView({ behavior: "smooth", block: "start" });
      }

      // Remove o foco do campo
      e.target.blur();
    }
  });
});

// ================================
// FAVORITOS (com localStorage)
// ================================

// Carrega favoritos do localStorage (array de IDs)
let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

// Atualiza a lista de favoritos no localStorage
function salvarFavoritos() {
  localStorage.setItem("favoritos", JSON.stringify(favoritos));
}

// Função para verificar se um produto está favoritado
function estaFavoritado(id) {
  return favoritos.includes(id);
}

// Modifica renderizarProdutos para mostrar o estado do favorito
function renderizarProdutos() {
  produtosContainer.innerHTML = "";

  let produtosFiltrados =
    categoriaSelecionada === "tudo"
      ? produtos.slice()
      : produtos.filter((p) => p.categoria === categoriaSelecionada);

  if (termoDeBusca.trim() !== "") {
    produtosFiltrados = produtosFiltrados.filter((p) =>
      p.nome.toLowerCase().includes(termoDeBusca.toLowerCase())
    );
  }

  const produtosVisiveis = produtosFiltrados.slice(
    0,
    paginaAtual * produtosPorPagina
  );

  produtosVisiveis.forEach((produto) => {
    const divProduto = document.createElement("div");
    divProduto.classList.add("produto");

    const precoAntigoHtml = produto.precoAntigo
      ? `<span class="preco-antigo">R$ ${produto.precoAntigo
          .toFixed(2)
          .replace(".", ",")}</span>`
      : "";

    // Define o botão favoritar com coração preenchido se estiver favoritado
    const coracao = estaFavoritado(produto.id) ? "♥" : "♡";
    const classeFavorito = estaFavoritado(produto.id) ? "favoritado" : "";

    divProduto.innerHTML = `
      <button class="btn-favoritar ${classeFavorito}" aria-label="Favoritar produto ${produto.nome}">${coracao}</button>
      <img src="${produto.imagem}" alt="Imagem do produto ${produto.nome}" />
      <div class="produto-info">
        <h3>${produto.nome}</h3>
        <div class="produto-preco">${precoAntigoHtml} R$ ${produto.preco
      .toFixed(2)
      .replace(".", ",")}</div>
      </div>
    `;

    produtosContainer.appendChild(divProduto);
  });

  botaoVerMais.style.display =
    produtosVisiveis.length >= produtosFiltrados.length ? "none" : "block";
}

// Atualiza favoritos ao clicar no botão
produtosContainer.addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-favoritar")) {
    // Pega o nome do produto para identificar qual botão clicou
    const produtoDiv = e.target.closest(".produto");
    const nomeProduto = produtoDiv.querySelector("h3").textContent;

    // Encontra o produto pelo nome (ou pode usar data-id se quiser)
    const produto = produtos.find((p) => p.nome === nomeProduto);
    if (!produto) return;

    const id = produto.id;

    if (estaFavoritado(id)) {
      // Remove dos favoritos
      favoritos = favoritos.filter((favId) => favId !== id);
      e.target.classList.remove("favoritado");
      e.target.textContent = "♡";
    } else {
      // Adiciona aos favoritos
      favoritos.push(id);
      e.target.classList.add("favoritado");
      e.target.textContent = "♥";
    }

    salvarFavoritos();
  }
});

// ================================
// CHAMA RENDERIZAÇÃO INICIAL
// ================================
renderizarProdutos();
 
const slider = document.querySelector(".hero-slider");
const slides = document.querySelectorAll(".hero-slider .slide");

let currentIndex = 0;
const intervalTime = 5000; // 5 segundos
let autoplay; // guarda o intervalo

function autoplaySlider() {
  atualizarDots(currentIndex);
  currentIndex = (currentIndex + 1) % slides.length;
  const scrollX = currentIndex * slider.offsetWidth;
  slider.scrollTo({ left: scrollX, behavior: "smooth" });
}

// Inicia o autoplay
function startAutoplay() {
  autoplay = setInterval(autoplaySlider, intervalTime);
}
function stopAutoplay() {
  clearInterval(autoplay);
}

startAutoplay();

// Pausar autoplay ao passar o mouse (desktop apenas)
slider.addEventListener("mouseenter", stopAutoplay);
slider.addEventListener("mouseleave", startAutoplay);

// ========== ARRASTAR COM MOUSE NO DESKTOP ==========
let isDragging = false;
let startX;
let scrollLeft;

slider.addEventListener("mousedown", (e) => {
  if (window.innerWidth < 769) return; // só desktop
  isDragging = true;
  slider.classList.add("dragging");
  startX = e.pageX - slider.offsetLeft;
  scrollLeft = slider.scrollLeft;
});

slider.addEventListener("mouseleave", () => {
  isDragging = false;
  slider.classList.remove("dragging");
});

slider.addEventListener("mouseup", () => {
  if (!isDragging) return;
  isDragging = false;
  slider.classList.remove("dragging");

  // Calcula slide mais próximo
  const slideWidth = slider.offsetWidth;
  const scrollX = slider.scrollLeft;
  const indexAproximado = Math.round(scrollX / slideWidth);
  const alinhamento = indexAproximado * slideWidth;

  slider.scrollTo({ left: alinhamento, behavior: "smooth" });
  currentIndex = indexAproximado; // sincroniza com autoplay
  atualizarDots(currentIndex);
});

slider.addEventListener("mousemove", (e) => {
  if (!isDragging) return;
  e.preventDefault();
  const x = e.pageX - slider.offsetLeft;
  const walk = (x - startX) * 1.2; // fator de arraste
  slider.scrollLeft = scrollLeft - walk;
});

// ==== DOTS FUNCIONAIS ====
const dots = document.querySelectorAll(".dot");

// Função para atualizar o estado ativo do dot
function atualizarDots(index) {
  dots.forEach((dot, i) => {
    dot.classList.toggle("active", i === index);
  });
}

// Quando clicar em um dot
dots.forEach((dot) => {
  dot.addEventListener("click", () => {
    const index = parseInt(dot.dataset.index);
    currentIndex = index;
    const scrollX = index * slider.offsetWidth;
    slider.scrollTo({ left: scrollX, behavior: "smooth" });
    atualizarDots(index);
  });
});
