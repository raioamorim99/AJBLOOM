
        document.addEventListener('DOMContentLoaded', function() {
            // Menu Mobile
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mainNav = document.getElementById('mainNav');
            
            mobileMenuBtn.addEventListener('click', function() {
                mainNav.classList.toggle('show');
            });
            
            // FAQ Accordion
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const item = this.parentElement;
                    item.classList.toggle('active');
                    
                    // Fecha outros itens ao abrir um novo
                    if (item.classList.contains('active')) {
                        faqQuestions.forEach(q => {
                            if (q.parentElement !== item) {
                                q.parentElement.classList.remove('active');
                            }
                        });
                    }
                });
            });
            
            // Smooth scrolling para links internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if (this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 100,
                                behavior: 'smooth'
                            });
                            
                            // Fecha o menu mobile se estiver aberto
                            if (mainNav.classList.contains('show')) {
                                mainNav.classList.remove('show');
                            }
                        }
                    }
                });
            });
        });
  