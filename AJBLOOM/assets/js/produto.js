
const produtos = [
  {
    id: 1,
    nome: "Camiseta Floral",
    preco: 79.9,
    precoAntigo: 99.9,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
       destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},

  {
    id: 2,
    nome: "Vestido Casual",
    preco: 129.9,
    precoAntigo: null,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8dmVzdGlkb3xlbnwwfHwwfHx8MA%3D%3D",
     destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
  },
  {
    id: 3,
    nome: "Brincos Delicados",
    preco: 49.9,
    precoAntigo: 59.9,
    categoria: "acessorios",
    imagem:
      "https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 4,
    nome: "Camiseta Estampada",
    preco: 89.9,
    precoAntigo: 109.9,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1738651875561-2f81f962338c?q=80&w=928&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 5,
    nome: "Vestido Elegante",
    preco: 159.9,
    precoAntigo: 199.9,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1617790274211-cbe0e677b425?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 6,
    nome: "Colar Fashion",
    preco: 79.9,
    precoAntigo: null,
    categoria: "acessorios",
    imagem:
      "https://plus.unsplash.com/premium_photo-1674255466849-b23fc5f5d3eb?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 7,
    nome: "Crooped Básica",
    preco: 59.9,
    precoAntigo: null,
    categoria: "camisetas",
    imagem:
      "https://images.unsplash.com/photo-1622116756680-fee39c84f9bd?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 8,
    nome: "Listrada",
    preco: 39.9,
    precoAntigo: 49.9,
    categoria: "acessorios",
    imagem:
      "https://images.unsplash.com/photo-1712811593599-d95c6fc6dece?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 9,
    nome: "Shorts Jeans",
    preco: 139.9,
    precoAntigo: 169.9,
    categoria: "vestidos",
    imagem:
      "https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8c2hvcnRzJTIwamVhbnN8ZW58MHx8MHx8fDA%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
},
  {
    id: 10,
    nome: "Conjunto",
    preco: 69.9,
    precoAntigo: 89.9,
    categoria: "camisetas",
    imagem:
      "https://plus.unsplash.com/premium_photo-1723553201287-ab9a8fbe57d1?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
   destaques: [
    "✓ Tecido 100% algodão",
    "✓ Estampa floral exclusiva",
    "✓ Ideal para dias quentes"
  ],
  relacionados: [4, 7, 10] // IDs de produtos relacionados
} 
];

// Captura ID da URL (ex: produto.html?id=3)
const urlParams = new URLSearchParams(window.location.search);
const id = parseInt(urlParams.get("id"));

// Seleciona o produto pelo ID exato
const produto = produtos.find(p => p.id === id);

// Seleciona os elementos HTML
const nomeEl = document.getElementById("produto-nome");
const precoEl = document.getElementById("produto-preco");
const descEl = document.getElementById("produto-desc");
const imgEl = document.getElementById("produto-img");

if (produto) {
  nomeEl.textContent = produto.nome;

  // Exibe preço com ou sem preço antigo
  if (produto.precoAntigo) {
    precoEl.innerHTML = `
      <span style="color: #555; text-decoration: line-through; margin-right: 10px;">
        R$ ${produto.precoAntigo.toFixed(2).replace(".", ",")}
      </span>
      <strong style="color: red;">
        R$ ${produto.preco.toFixed(2).replace(".", ",")}
      </strong>
    `;
  } else {
    precoEl.innerHTML = `<strong>R$ ${produto.preco.toFixed(2).replace(".", ",")}</strong>`;
  }

  descEl.textContent = produto.categoria?.charAt(0).toUpperCase() + produto.categoria?.slice(1) + " • " + produto.nome;
  imgEl.src = produto.imagem;
  imgEl.alt = `Imagem do produto ${produto.nome}`;

  const btnPedido = document.querySelector(".btn-pedido");
const numeroWhatsApp = "5541999999999"; // seu número com DDD
const mensagem = `Olá! Gostei do produto "${produto.nome}" no site AJBLOOM. Ainda está disponível?`;
const linkWhats = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagem)}`;
btnPedido.href = linkWhats;


  btnFav.addEventListener("click", () => {
    alert(`Você favoritou: ${produto.nome}`);
  });
} else {
  nomeEl.textContent = "Produto não encontrado";
  precoEl.style.display = "none";
  descEl.textContent = "O produto que você está tentando acessar não está disponível.";
  imgEl.style.display = "none";
  btnFav.style.display = "none";
}

const msgVazio           = document.getElementById("msg-vazio");
const btnVoltar          = document.querySelector(".btn-voltar");


// 7. “Voltar à loja”
btnVoltar.addEventListener("click", e => {
  e.preventDefault();
  window.location.href = "index.html";
});

/* home */ 
const botaoHome = document.getElementById("home.link");

if (botaoHome) {
  botaoHome.addEventListener("click", function (e) {
    e.preventDefault();
    // Verifica se já está na página principal
    if (window.location.pathname.includes("index.html") || window.location.pathname === "/" || window.location.pathname === "/index") {
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      // Redireciona para a home
      window.location.href = "index.html";
    }
  });
}


