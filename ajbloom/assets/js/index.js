document.addEventListener("DOMContentLoaded", () => {
  // ================================
  // CONFIGURAÇÕES GLOBAIS
  // ================================
  const CONFIG = {
    API_URL: "../backend/API/produtos.php",
    PRODUTOS_POR_PAGINA: 10,
    DEBOUNCE_DELAY: 300,
  }

  // ================================
  // ELEMENTOS DOM
  // ================================
  const elementos = {
    menuToggle: document.getElementById("menu-toggle"),
    sideMenu: document.getElementById("side-menu"),
    overlay: document.getElementById("overlay"),
    closeMenuBtn: document.getElementById("close-menu"),
    produtosContainer: document.getElementById("produtos"),
    filtroCategoria: document.getElementById("filtro-categoria"),
    botaoVerMais: document.getElementById("ver-mais"),
    searchInputs: document.querySelectorAll(".search-input"),
    loadingProdutos: document.getElementById("loading-produtos"),
    header: document.querySelector("header"),
  }

  // ================================
  // ESTADO DA APLICAÇÃO
  // ================================
  const estado = {
    paginaAtual: 1,
    categoriaSelecionada: "tudo",
    termoDeBusca: "",
    carregando: false,
    temMais: true,
    produtosCarregados: [],
  }

  // ================================
  // FUNÇÕES DE MENU RESPONSIVO CORRIGIDAS
  // ================================
  function abrirMenu() {
    console.log("🍔 Abrindo menu lateral")
    elementos.sideMenu.classList.add("open")
    elementos.menuToggle.classList.add("open")
    elementos.overlay.classList.add("active")
    elementos.sideMenu.setAttribute("aria-hidden", "false")
    elementos.menuToggle.setAttribute("aria-expanded", "true")
    document.body.style.overflow = "hidden"

    // ✅ CORRIGIDO: Garantir que o menu esteja acima do overlay
    elementos.sideMenu.style.zIndex = "2000"
    elementos.overlay.style.zIndex = "1500"

    elementos.sideMenu.focus()
  }

  function fecharMenu() {
    console.log("❌ Fechando menu lateral")
    elementos.sideMenu.classList.remove("open")
    elementos.menuToggle.classList.remove("open")
    elementos.overlay.classList.remove("active")
    elementos.sideMenu.setAttribute("aria-hidden", "true")
    elementos.menuToggle.setAttribute("aria-expanded", "false")
    document.body.style.overflow = ""
    elementos.menuToggle.focus()
  }

  // ================================
  // FUNÇÕES DE LOADING
  // ================================
  function mostrarLoading() {
    if (elementos.loadingProdutos) {
      elementos.loadingProdutos.style.display = "flex"
      estado.carregando = true
    }
  }

  function esconderLoading() {
    if (elementos.loadingProdutos) {
      elementos.loadingProdutos.style.display = "none"
      estado.carregando = false
    }
  }

  // ================================
  // FUNÇÃO PARA CORRIGIR CAMINHO DA IMAGEM
  // ================================
  function corrigirCaminhoImagem(imagem) {
    if (!imagem) {
      return "/placeholder.svg?height=400&width=400&text=Sem+Imagem"
    }

    // Se é URL externa, retorna como está
    if (imagem.startsWith("http")) {
      return imagem
    }

    // Remove barras duplas e normaliza o caminho
    imagem = imagem.replace(/^\/+/, "")

    // Se já começa com assets/, adiciona apenas ../
    if (imagem.startsWith("assets/")) {
      return "../" + imagem
    }

    // Se não tem assets/, assume que está na pasta de produtos
    if (!imagem.includes("assets/")) {
      return "../assets/imagens/produtos/" + imagem.split("/").pop()
    }

    // Caso padrão
    return "../" + imagem
  }

  // ================================
  // API - BUSCAR PRODUTOS
  // ================================
  async function buscarProdutos(filtros = {}, substituir = false) {
    if (estado.carregando) return

    mostrarLoading()

    const params = new URLSearchParams({
      busca: filtros.busca || estado.termoDeBusca,
      categoria: filtros.categoria || estado.categoriaSelecionada,
      limite: CONFIG.PRODUTOS_POR_PAGINA,
      offset: substituir ? 0 : (estado.paginaAtual - 1) * CONFIG.PRODUTOS_POR_PAGINA,
    })

    try {
      const response = await fetch(`${CONFIG.API_URL}?${params}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (!data.success) {
        throw new Error(data.error || "Erro desconhecido")
      }

      esconderLoading()
      return data
    } catch (error) {
      console.error("Erro ao buscar produtos:", error)
      esconderLoading()

      mostrarMensagemErro("Erro ao carregar produtos. Tente novamente.")

      return {
        success: false,
        produtos: [],
        total: 0,
        tem_mais: false,
      }
    }
  }

  // ================================
  // RENDERIZAÇÃO DE PRODUTOS
  // ================================
  function criarElementoProduto(produto) {
    const divProduto = document.createElement("div")
    divProduto.classList.add("produto")
    divProduto.setAttribute("data-categoria", produto.categoria)

    const precoAntigoHtml = produto.preco_antigo
      ? `<span class="preco-antigo">R$ ${Number.parseFloat(produto.preco_antigo).toFixed(2).replace(".", ",")}</span>`
      : ""

    const badgeLancamento = produto.lancamento == 1 ? `<span class="badge badge-lancamento">Lançamento</span>` : ""

    const badgeVendido = produto.mais_vendido == 1 ? `<span class="badge badge-vendido">Mais Vendido</span>` : ""

    // Corrigir caminho da imagem
    const imagemCorrigida = corrigirCaminhoImagem(produto.imagem)

    divProduto.innerHTML = `
      <a href="produto.php?id=${produto.id}">
        <img src="${imagemCorrigida}" 
             alt="Imagem do produto ${produto.nome}" 
             loading="lazy"
             onerror="this.src='/placeholder.svg?height=400&width=400&text=Erro+Imagem'; this.style.background='#f8f9fa';" />
      </a>
      <div class="produto-info">
        <a href="produto.php?id=${produto.id}">
          <h3>${produto.nome}</h3>
        </a>
        <div class="produto-preco">
          ${precoAntigoHtml} 
          R$ ${Number.parseFloat(produto.preco).toFixed(2).replace(".", ",")}
        </div>
        ${badgeLancamento}
        ${badgeVendido}
      </div>
    `

    return divProduto
  }

  async function renderizarProdutos(substituir = false) {
    const data = await buscarProdutos({}, substituir)

    if (!data.success) return

    if (substituir) {
      elementos.produtosContainer.innerHTML = ""
      estado.produtosCarregados = []
    }

    // Adicionar novos produtos
    data.produtos.forEach((produto) => {
      const elementoProduto = criarElementoProduto(produto)
      elementos.produtosContainer.appendChild(elementoProduto)
      estado.produtosCarregados.push(produto)
    })

    // Botão Ver Mais sempre redireciona para vitrine.php
    if (elementos.botaoVerMais) {
      elementos.botaoVerMais.style.display = "block"
      elementos.botaoVerMais.textContent = "Ver todos os produtos"
    }

    // Mostrar mensagem se não há produtos
    if (estado.produtosCarregados.length === 0) {
      mostrarMensagemVazia()
    }
  }

  // ================================
  // MENSAGENS DE FEEDBACK
  // ================================
  function mostrarMensagemErro(mensagem) {
    const errorDiv = document.createElement("div")
    errorDiv.className = "mensagem-erro"
    errorDiv.innerHTML = `
      <p>${mensagem}</p>
      <button onclick="location.reload()">Tentar novamente</button>
    `
    elementos.produtosContainer.appendChild(errorDiv)
  }

  function mostrarMensagemVazia() {
    const emptyDiv = document.createElement("div")
    emptyDiv.className = "mensagem-vazia"
    emptyDiv.innerHTML = `
      <p>Nenhum produto encontrado.</p>
      <p>Tente ajustar os filtros de busca.</p>
    `
    elementos.produtosContainer.appendChild(emptyDiv)
  }

  // ================================
  // FILTROS E BUSCA
  // ================================
  async function aplicarFiltros() {
    estado.paginaAtual = 1
    await renderizarProdutos(true)
  }

  function debounce(func, delay) {
    let timeout
    return function (...args) {
      clearTimeout(timeout)
      timeout = setTimeout(() => func.apply(this, args), delay)
    }
  }

  const handleSearchInput = debounce(async (event) => {
    estado.termoDeBusca = event.target.value.trim()
    await aplicarFiltros()
  }, CONFIG.DEBOUNCE_DELAY)

  // ================================
  // RESPONSIVIDADE DO HEADER
  // ================================
  function handleHeaderScroll() {
    if (!elementos.header) return

    const scrolled = window.scrollY > 50
    elementos.header.classList.toggle("scrolled", scrolled)
  }

  // ================================
  // SLIDER RESPONSIVO MELHORADO
  // ================================
  function initSlider() {
    const slider = document.querySelector(".hero-slider .slides")
    const slides = document.querySelectorAll(".hero-slider .slide")
    const dots = document.querySelectorAll(".dot")

    if (!slider || slides.length === 0) {
      console.log("❌ Slider não encontrado ou sem slides")
      return
    }

    let currentIndex = 0
    let autoplayInterval
    let isUserInteracting = false
    let isMouseOver = false
    let isDragging = false
    let startX = 0
    let scrollLeft = 0
    const dragThreshold = 50

    function goToSlide(index) {
      currentIndex = index
      const slideWidth = slider.offsetWidth

      slider.scrollTo({
        left: currentIndex * slideWidth,
        behavior: "smooth",
      })

      dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentIndex)
      })

      console.log(`📍 Slide atual: ${currentIndex + 1}/${slides.length}`)
    }

    function nextSlide() {
      if (isUserInteracting || isMouseOver || isDragging) return
      currentIndex = (currentIndex + 1) % slides.length
      goToSlide(currentIndex)
    }

    function startAutoplay() {
      if (autoplayInterval) clearInterval(autoplayInterval)
      autoplayInterval = setInterval(nextSlide, 5000)
      console.log("▶️ Autoplay iniciado")
    }

    function stopAutoplay() {
      if (autoplayInterval) {
        clearInterval(autoplayInterval)
        autoplayInterval = null
        console.log("⏸️ Autoplay pausado")
      }
    }

    // Event listeners para dots
    dots.forEach((dot, index) => {
      dot.addEventListener("click", (e) => {
        e.preventDefault()
        console.log(`🎯 Dot ${index + 1} clicado`)
        isUserInteracting = true
        goToSlide(index)
        stopAutoplay()

        setTimeout(() => {
          isUserInteracting = false
          if (!isMouseOver) startAutoplay()
        }, 3000)
      })

      dot.setAttribute("data-slide", index)
    })

    // Mouse events para controlar autoplay
    slider.addEventListener("mouseenter", () => {
      isMouseOver = true
      stopAutoplay()
      console.log("🖱️ Mouse sobre o slider - autoplay pausado")
    })

    slider.addEventListener("mouseleave", () => {
      isMouseOver = false
      if (!isUserInteracting && !isDragging) {
        startAutoplay()
        console.log("🖱️ Mouse saiu do slider - autoplay retomado")
      }
    })

    // Touch/Drag events com threshold
    slider.addEventListener(
      "touchstart",
      (e) => {
        isUserInteracting = true
        isDragging = true
        startX = e.touches[0].pageX
        scrollLeft = slider.scrollLeft
        stopAutoplay()
      },
      { passive: true },
    )

    slider.addEventListener(
      "touchmove",
      (e) => {
        if (!isDragging) return
        const x = e.touches[0].pageX
        const walk = (x - startX) * 1.5
        slider.scrollLeft = scrollLeft - walk
      },
      { passive: true },
    )

    slider.addEventListener("touchend", (e) => {
      if (!isDragging) return
      isDragging = false

      const endX = e.changedTouches[0].pageX
      const diff = startX - endX

      if (Math.abs(diff) > dragThreshold) {
        if (diff > 0 && currentIndex < slides.length - 1) {
          currentIndex++
        } else if (diff < 0 && currentIndex > 0) {
          currentIndex--
        }
      }

      goToSlide(currentIndex)

      setTimeout(() => {
        isUserInteracting = false
        if (!isMouseOver) startAutoplay()
      }, 2000)
    })

    // Mouse drag para desktop com threshold
    slider.addEventListener("mousedown", (e) => {
      isUserInteracting = true
      isDragging = true
      startX = e.pageX
      scrollLeft = slider.scrollLeft
      stopAutoplay()
      slider.style.cursor = "grabbing"
      e.preventDefault()
    })

    slider.addEventListener("mousemove", (e) => {
      if (!isDragging) return
      e.preventDefault()
      const x = e.pageX
      const walk = (x - startX) * 1.2
      slider.scrollLeft = scrollLeft - walk
    })

    slider.addEventListener("mouseup", (e) => {
      if (!isDragging) return
      isDragging = false
      slider.style.cursor = "grab"

      const endX = e.pageX
      const diff = startX - endX

      if (Math.abs(diff) > dragThreshold) {
        if (diff > 0 && currentIndex < slides.length - 1) {
          currentIndex++
        } else if (diff < 0 && currentIndex > 0) {
          currentIndex--
        }
      }

      goToSlide(currentIndex)

      setTimeout(() => {
        isUserInteracting = false
        if (!isMouseOver) startAutoplay()
      }, 2000)
    })

    slider.addEventListener("mouseleave", () => {
      if (isDragging) {
        isDragging = false
        slider.style.cursor = "grab"
      }
    })

    // Keyboard navigation
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        e.preventDefault()
        isUserInteracting = true
        const prevIndex = currentIndex === 0 ? slides.length - 1 : currentIndex - 1
        goToSlide(prevIndex)
        stopAutoplay()
        setTimeout(() => {
          isUserInteracting = false
          if (!isMouseOver) startAutoplay()
        }, 3000)
      } else if (e.key === "ArrowRight") {
        e.preventDefault()
        isUserInteracting = true
        const nextIndex = (currentIndex + 1) % slides.length
        goToSlide(nextIndex)
        stopAutoplay()
        setTimeout(() => {
          isUserInteracting = false
          if (!isMouseOver) startAutoplay()
        }, 3000)
      }
    })

    // Inicializar
    slider.style.cursor = "grab"
    goToSlide(0)
    startAutoplay()

    console.log(`✅ Slider inicializado com ${slides.length} slides`)
  }

  // ================================
  // ✅ LOOKBOOK MOBILE SCROLL EFFECT
  // ================================
  function initLookbookMobileEffect() {
    const lookbookScroll = document.querySelector(".lookbook-scroll")
    const lookbookItems = document.querySelectorAll(".lookbook-item")

    if (!lookbookScroll || lookbookItems.length === 0) return

    // ✅ Só aplicar no mobile
    if (window.innerWidth > 768) return

    function updateVisibleItems() {
      const scrollLeft = lookbookScroll.scrollLeft
      const containerWidth = lookbookScroll.offsetWidth
      const centerPoint = scrollLeft + containerWidth / 2

      lookbookItems.forEach((item, index) => {
        const itemLeft = item.offsetLeft
        const itemWidth = item.offsetWidth
        const itemCenter = itemLeft + itemWidth / 2

        // ✅ Item está no centro da tela
        const distanceFromCenter = Math.abs(centerPoint - itemCenter)
        const threshold = itemWidth / 2

        if (distanceFromCenter < threshold) {
          item.classList.add("in-view")
        } else {
          item.classList.remove("in-view")
        }
      })
    }

    // ✅ Listener para scroll
    lookbookScroll.addEventListener("scroll", updateVisibleItems, { passive: true })

    // ✅ Inicializar
    updateVisibleItems()

    console.log("✅ Lookbook mobile effect inicializado")
  }

  // ================================
  // EVENT LISTENERS
  // ================================

  // ✅ CORRIGIDO: Menu responsivo com logs e z-index
  if (elementos.menuToggle) {
    elementos.menuToggle.addEventListener("click", (e) => {
      e.stopPropagation()
      console.log("🍔 Menu toggle clicado")
      if (elementos.sideMenu.classList.contains("open")) {
        fecharMenu()
      } else {
        abrirMenu()
      }
    })
    console.log("✅ Event listener do menu hambúrguer configurado")
  } else {
    console.log("❌ Menu toggle não encontrado")
  }

  if (elementos.closeMenuBtn) {
    elementos.closeMenuBtn.addEventListener("click", (e) => {
      e.stopPropagation()
      fecharMenu()
    })
    console.log("✅ Event listener do botão fechar menu configurado")
  }

  if (elementos.overlay) {
    elementos.overlay.addEventListener("click", fecharMenu)
    console.log("✅ Event listener do overlay configurado")
  }

  // ✅ CORRIGIDO: Fechar menu ao clicar em links
  elementos.sideMenu?.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", (e) => {
      // ✅ Não prevenir o comportamento padrão, apenas fechar o menu
      fecharMenu()
    })
  })

  // Fechar menu com ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && elementos.sideMenu?.classList.contains("open")) {
      fecharMenu()
    }
  })

  // Filtros funcionando
  if (elementos.filtroCategoria) {
    elementos.filtroCategoria.addEventListener("change", async (e) => {
      console.log("🔍 Filtro categoria alterado:", e.target.value)
      estado.categoriaSelecionada = e.target.value === "tudo" ? "" : e.target.value
      estado.termoDeBusca = ""
      elementos.searchInputs.forEach((input) => (input.value = ""))
      await aplicarFiltros()
    })
  }

  // Botão Ver Mais redireciona para vitrine.php
  if (elementos.botaoVerMais) {
    elementos.botaoVerMais.addEventListener("click", (e) => {
      e.preventDefault()
      console.log("🔄 Redirecionando para vitrine.php")
      window.location.href = "vitrine.php"
    })
    console.log("✅ Event listener do botão Ver Mais configurado")
  }

  // Busca responsiva funcionando
  elementos.searchInputs.forEach((input) => {
    if (!input) return

    input.addEventListener("input", (e) => {
      console.log("🔍 Busca alterada:", e.target.value)
      handleSearchInput(e)
    })

    input.addEventListener("keydown", async (e) => {
      if (e.key === "Enter") {
        e.preventDefault()
        estado.termoDeBusca = e.target.value.trim()
        console.log("🔍 Busca Enter:", estado.termoDeBusca)
        await aplicarFiltros()

        const produtosSection = document.getElementById("destaques")
        if (produtosSection) {
          produtosSection.scrollIntoView({ behavior: "smooth", block: "start" })
        }

        e.target.blur()
      }
    })
  })

  // Scroll do header
  window.addEventListener("scroll", handleHeaderScroll, { passive: true })

  // ✅ CORRIGIDO: Resize responsivo com lookbook mobile
  window.addEventListener(
    "resize",
    debounce(() => {
      const slider = document.querySelector(".hero-slider .slides")
      if (slider) {
        const currentSlide = Math.round(slider.scrollLeft / slider.offsetWidth)
        slider.scrollTo({ left: currentSlide * slider.offsetWidth, behavior: "smooth" })
      }

      // ✅ Reinicializar lookbook mobile effect
      initLookbookMobileEffect()
    }, 250),
    { passive: true },
  )

  // ================================
  // ANIMAÇÕES AO ROLAR
  // ================================
  function initScrollAnimations() {
    const elementsToAnimate = document.querySelectorAll(".animate-on-scroll")

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("active")
            observer.unobserve(entry.target)
          }
        })
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      },
    )

    elementsToAnimate.forEach((el) => observer.observe(el))
  }

  // ================================
  // SCROLL SUAVE
  // ================================
  function initSmoothScroll() {
    document.querySelectorAll(".home-link").forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault()
        window.scrollTo({ top: 0, behavior: "smooth" })
      })
    })

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault()
        const target = document.querySelector(this.getAttribute("href"))
        if (target) {
          const headerHeight = elementos.header?.offsetHeight || 80
          window.scrollTo({
            top: target.offsetTop - headerHeight - 20,
            behavior: "smooth",
          })
        }
      })
    })
  }

  // ================================
  // MODAL LOOKBOOK
  // ================================
  function initLookbookModal() {
    function abrirLookModal(look) {
      const modal = document.getElementById("look-modal")
      const img = document.getElementById("look-img")
      const nome = document.getElementById("look-nome")
      const desc = document.getElementById("look-desc")
      const btn = document.getElementById("look-btn")

      if (modal && img && nome && desc && btn) {
        img.src = look.img
        nome.textContent = look.nome
        desc.textContent = look.desc
        btn.href = look.link
        modal.classList.add("ativo")
        document.body.style.overflow = "hidden"
      }
    }

    function fecharLookModal() {
      const modal = document.getElementById("look-modal")
      if (modal) {
        modal.classList.remove("ativo")
        document.body.style.overflow = ""
      }
    }

    // Tornar funções globais
    window.abrirLookModal = abrirLookModal
    window.fecharLookModal = fecharLookModal

    // ✅ ATUALIZADO: Dados dos looks com nomes corretos dos produtos
    const looks = [
      {
        nome: "Conjunto Girassol",
        desc: "Conjunto elegante com estampa floral vibrante, perfeito para ocasiões especiais.",
        img: "../assets/imagens/produtos/conjuntos/conjunto_girassol.webp",
        link: "vitrine.php?categoria=conjuntos",
      },
      {
        nome: "Conjunto Margarida",
        desc: "Delicadeza e sofisticação em um conjunto único com detalhes florais.",
        img: "../assets/imagens/produtos/conjuntos/conjunto_margarida.webp",
        link: "vitrine.php?categoria=conjuntos",
      },
      {
        nome: "Conjunto Orquídea",
        desc: "Elegância refinada com toque moderno para mulheres autênticas.",
        img: "../assets/imagens/produtos/conjuntos/conjunto_orquidea.webp",
        link: "vitrine.php?categoria=conjuntos",
      },
      {
        nome: "Saia Lírio",
        desc: "Saia versátil que combina com diversos looks, do casual ao elegante.",
        img: "../assets/imagens/produtos/saias/saia_lirio.webp",
        link: "vitrine.php?categoria=saias",
      },
      {
        nome: "Vestido Bambu",
        desc: "Vestido fluido e confortável, ideal para o dia a dia com muito estilo.",
        img: "../assets/imagens/produtos/vestidos/vestido_bambu.webp",
        link: "vitrine.php?categoria=vestidos",
      },
      {
        nome: "Vestido Hortênsia",
        desc: "Vestido romântico com detalhes únicos para momentos especiais.",
        img: "../assets/imagens/produtos/vestidos/vestido_hortensia.webp",
        link: "vitrine.php?categoria=vestidos",
      },
      {
        nome: "Body Lavanda",
        desc: "Body moderno e versátil, perfeito para compor looks incríveis.",
        img: "../assets/imagens/produtos/borys/body_lavanda-2.webp",
        link: "vitrine.php?categoria=borys",
      },
      {
        nome: "Body Violeta",
        desc: "Body elegante com cor vibrante para mulheres que gostam de se destacar.",
        img: "../assets/imagens/produtos/borys/body_violeta-2.webp",
        link: "vitrine.php?categoria=borys",
      },
    ]

    // Event listeners para cards do lookbook
    document.querySelectorAll(".lookbook-item").forEach((item, i) => {
      item.addEventListener("click", () => abrirLookModal(looks[i]))

      // Acessibilidade
      item.setAttribute("role", "button")
      item.setAttribute("tabindex", "0")
      item.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault()
          abrirLookModal(looks[i])
        }
      })
    })

    // Fechar modal com ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        fecharLookModal()
      }
    })
  }

  // ================================
  // PÉTALAS FLUTUANTES
  // ================================
  function iniciarPetalas(containerId, quantidade = 15) {
    const container = document.getElementById(containerId)
    if (!container) return

    // Limpar pétalas existentes
    container.innerHTML = ""

    for (let i = 0; i < quantidade; i++) {
      const petala = document.createElement("div")
      petala.classList.add("petala")
      petala.textContent = "❀"

      petala.style.left = Math.random() * 100 + "vw"
      petala.style.animationDuration = 4 + Math.random() * 6 + "s"
      petala.style.animationDelay = Math.random() * 4 + "s"
      petala.style.opacity = 0.3 + Math.random() * 0.5
      petala.style.fontSize = 14 + Math.random() * 12 + "px"
      petala.style.color = "#7db9e8"

      container.appendChild(petala)
    }
  }

  // ================================
  // OTIMIZAÇÕES DE PERFORMANCE
  // ================================
  function initPerformanceOptimizations() {
    // Lazy loading para imagens
    if ("IntersectionObserver" in window) {
      const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const img = entry.target
            if (img.dataset.src) {
              img.src = img.dataset.src
              img.removeAttribute("data-src")
              imageObserver.unobserve(img)
            }
          }
        })
      })

      document.querySelectorAll("img[data-src]").forEach((img) => {
        imageObserver.observe(img)
      })
    }

    // Preload de recursos críticos
    const criticalResources = ["../assets/imagens/logo/logo.png", "../assets/imagens/banners/banner-bem-vinda.jpg"]

    criticalResources.forEach((resource) => {
      const link = document.createElement("link")
      link.rel = "preload"
      link.as = "image"
      link.href = resource
      document.head.appendChild(link)
    })
  }

  // ================================
  // INICIALIZAÇÃO
  // ================================
  function init() {
    console.log("🌸 AJBLOOM - Sistema responsivo inicializado!")

    // Verificar elementos críticos
    console.log("🔍 Verificando elementos:")
    console.log("- Menu toggle:", !!elementos.menuToggle)
    console.log("- Side menu:", !!elementos.sideMenu)
    console.log("- Overlay:", !!elementos.overlay)
    console.log("- Close menu btn:", !!elementos.closeMenuBtn)
    console.log("- Botão Ver Mais:", !!elementos.botaoVerMais)
    console.log("- Container produtos:", !!elementos.produtosContainer)
    console.log("- Filtro categoria:", !!elementos.filtroCategoria)
    console.log("- Inputs de busca:", elementos.searchInputs.length)

    // Inicializar componentes
    initSlider()
    initScrollAnimations()
    initSmoothScroll()
    initLookbookModal()
    initLookbookMobileEffect() // ✅ NOVO: Efeito mobile do lookbook
    initPerformanceOptimizations()

    // Inicializar pétalas
    iniciarPetalas("petalas-lookbook")

    // Header inicial
    handleHeaderScroll()

    // Carregar produtos iniciais
    renderizarProdutos(true)

    console.log("✅ LOOKBOOK ATUALIZADO:")
    console.log("- Nomes dos produtos corretos")
    console.log("- Descrições personalizadas")
    console.log("- Links para categorias específicas")
  }

  // Inicializar quando DOM estiver pronto
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init)
  } else {
    init()
  }
})
