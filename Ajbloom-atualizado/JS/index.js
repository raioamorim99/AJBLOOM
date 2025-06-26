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

// Fecha o menu lateral ao clicar em qualquer link
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
    imagem: "https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 2,
    nome: "Vestido Casual",
    preco: 129.9,
    precoAntigo: null,
    categoria: "vestidos",
    imagem: "https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop"
  },
  {
    id: 3,
    nome: "Brincos Delicados",
    preco: 49.9,
    precoAntigo: 59.9,
    categoria: "acessorios",
    imagem: "https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 4,
    nome: "Camiseta Estampada",
    preco: 89.9,
    precoAntigo: 109.9,
    categoria: "camisetas",
    imagem: "https://images.unsplash.com/photo-1738651875561-2f81f962338c?q=80&w=928&auto=format&fit=crop"
  },
  {
    id: 5,
    nome: "Vestido Elegante",
    preco: 159.9,
    precoAntigo: 199.9,
    categoria: "vestidos",
    imagem: "https://images.unsplash.com/photo-1617790274211-cbe0e677b425?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 6,
    nome: "Colar Fashion",
    preco: 79.9,
    precoAntigo: null,
    categoria: "acessorios",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 7,
    nome: "Crooped Básica",
    preco: 59.9,
    precoAntigo: null,
    categoria: "camisetas",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 8,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  },
  {
    id: 9,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 10,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 11,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 12,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 13,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 14,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 15,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 16,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 17,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 18,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 19,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
  ,
  {
    id: 20,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem: "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop"
  }
];

const produtosPorPagina = 6;
let paginaAtual = 1;
let categoriaSelecionada = "tudo";
let termoDeBusca = "";

const produtosContainer = document.getElementById("produtos");
const filtroCategoria = document.getElementById("filtro-categoria");
const botaoVerMais = document.getElementById("ver-mais");
const searchInputs = document.querySelectorAll(".search-input");

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
      ? `<span class="preco-antigo">R$ ${produto.precoAntigo.toFixed(2).replace(".", ",")}</span>`
      : "";

    divProduto.innerHTML = `
      <a href="produtos.html?id=${produto.id}">
        <img src="${produto.imagem}" alt="Imagem do produto ${produto.nome}" />
      </a>
      <div class="produto-info">
        <a href="produtos.html?id=${produto.id}">
          <h3>${produto.nome}</h3>
        </a>
        <div class="produto-preco">
          ${precoAntigoHtml} R$ ${produto.preco.toFixed(2).replace(".", ",")}
        </div>
      </div>
    `;

    produtosContainer.appendChild(divProduto);
  });

  botaoVerMais.style.display =
    produtosVisiveis.length >= produtosFiltrados.length ? "none" : "block";
}

filtroCategoria.addEventListener("change", (e) => {
  categoriaSelecionada = e.target.value;
  paginaAtual = 1;
  termoDeBusca = "";
  searchInputs.forEach((input) => (input.value = ""));
  renderizarProdutos();
});

botaoVerMais.addEventListener("click", () => {
  paginaAtual++;
  renderizarProdutos();
});

function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

const handleSearchInput = debounce((event) => {
  termoDeBusca = event.target.value.trim();
  paginaAtual = 1;
  renderizarProdutos();
}, 200);

searchInputs.forEach((input) => {
  input.addEventListener("input", handleSearchInput);
  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      termoDeBusca = e.target.value.trim();
      paginaAtual = 1;
      renderizarProdutos();

      const produtosSection = document.getElementById("destaques");
      if (produtosSection) {
        produtosSection.scrollIntoView({ behavior: "smooth", block: "start" });
      }

      e.target.blur();
    }
  });
});

renderizarProdutos();
window.produtos = produtos;

// Slider automático
const slider = document.querySelector(".hero-slider");
const slides = document.querySelectorAll(".hero-slider .slide");

let currentIndex = 0;
const intervalTime = 5000;
let autoplay;

function autoplaySlider() {
  currentIndex = (currentIndex + 1) % slides.length;
  const scrollX = currentIndex * slider.offsetWidth;
  slider.scrollTo({ left: scrollX, behavior: "smooth" });
}

function startAutoplay() {
  autoplay = setInterval(autoplaySlider, intervalTime);
}
function stopAutoplay() {
  clearInterval(autoplay);
}

startAutoplay();
slider.addEventListener("mouseenter", stopAutoplay);
slider.addEventListener("mouseleave", startAutoplay);

// Slider - Drag
let isDragging = false;
let startX;
let scrollLeft;

slider.addEventListener("mousedown", (e) => {
  if (window.innerWidth < 769) return;
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

  const slideWidth = slider.offsetWidth;
  const scrollX = slider.scrollLeft;
  const indexAproximado = Math.round(scrollX / slideWidth);
  const alinhamento = indexAproximado * slideWidth;

  slider.scrollTo({ left: alinhamento, behavior: "smooth" });
  currentIndex = indexAproximado;
});

slider.addEventListener("mousemove", (e) => {
  if (!isDragging) return;
  e.preventDefault();
  const x = e.pageX - slider.offsetLeft;
  const walk = (x - startX) * 1.2;
  slider.scrollLeft = scrollLeft - walk;
});

// Animação ao rolar
document.addEventListener("DOMContentLoaded", () => {
  const elementsToAnimate = document.querySelectorAll(".animate-on-scroll");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("active");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  elementsToAnimate.forEach((el) => observer.observe(el));
});

document.querySelectorAll(".home-link").forEach(link => {
  link.addEventListener("click", function (e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});

// Scroll suave para os CTAs de âncora
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      window.scrollTo({
        top: target.offsetTop - 60, // ajuste se tiver header fixo
        behavior: 'smooth'
      });
    }
  });
});
