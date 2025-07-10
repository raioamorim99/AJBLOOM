-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/07/2025 às 21:23
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ajbloom_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `telefone`, `cpf`, `senha`) VALUES
(1, 'Ajbloom Teste', 'cliente@ajbloom.com', '(11) 99999-9999', '123.456.789-00', '$2y$10$vIEwNFi6YpLz5m6xK46uueK5KK5RLtxKlm5Yx9fnGEF/Y49Z0slCa'),
(2, 'Ajbloom Teste 2', 'teste2@ajbloom.com', '(11) 91111-2222', '321.654.987-00', '$2y$10$YHx5Zsy1bENwz2GdYhAmz.dAcDfnbzqRYVCf.1djNklBG0xlkHdRK');

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`id`, `cliente_id`, `rua`, `cidade`, `estado`, `cep`) VALUES
(2, 1, 'Rua das Flores, 123', 'São Paulo', 'SP', '01234-567'),
(3, 2, 'Rua Nova, 456', 'Rio de Janeiro', 'RJ', '22345-678');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `produto_nome` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `cliente_id`, `produto_nome`, `preco`) VALUES
(1, 2, 'Vestido Floral Curto', 129.90),
(2, 2, 'Calça Wide Leg Jeans', 159.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `numero_pedido` varchar(20) DEFAULT NULL,
  `data_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `numero_pedido`, `data_entrega`) VALUES
(1, 2, '12346', '2025-07-06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `preco_antigo` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `imagem` text DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `preco_antigo`, `categoria`, `imagem`, `descricao`) VALUES
(1, 'Camiseta Floral', 79.90, 99.90, 'camisetas', 'https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop', '✓ Tecido 100% algodão<br>✓ Estampa floral exclusiva<br>✓ Ideal para dias quentes'),
(2, 'Vestido Casual', 129.90, NULL, 'vestidos', 'https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop', '✓ Tecido leve<br>✓ Ideal para eventos e dias quentes'),
(3, 'Brincos Delicados', 49.90, 59.90, 'acessorios', 'https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop', '✓ Acabamento delicado<br>✓ Perfeito para looks leves'),
(4, 'Conjunto', 69.90, 89.90, 'camisetas', 'https://plus.unsplash.com/premium_photo-1723553201287-ab9a8fbe57d1?q=80&w=774&auto=format&fit=crop', '✓ Conjunto confortável<br>✓ Ideal para dia a dia'),
(10, 'Vestido Floral Curto', 129.90, NULL, NULL, NULL, NULL),
(11, 'Calça Wide Leg Jeans', 159.90, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `telefone`, `data_criacao`, `data_nascimento`) VALUES
(1, 'luan raio amorim', 'raioamorimluan@gmail.com', '$2y$10$24DnoPaVEcww.vnuztGiMeZzbA5XAU92mlW5jrp7Jh14d2nYzyl6q', '11916669945', '2025-07-08 01:12:10', NULL),
(13, 'Ajbloom teste', 'Ajbloom@teste.com', '$2y$10$37yPBAuF05I0cAWv7SIK7OXTsV1gXKatqpDJkhNsf3sOIQYm5XUJu', '+55 (11) 91422-8823', '2025-07-09 03:55:27', '2007-03-20'),
(14, 'aaaaaaaaaaaaaa', 'Ajbloom@tessttttt.com', '$2y$10$xnZK1Lgbf6ZjaeBBadh4Ge2e/YUvhz.6By1aXp65ZLKSENnxkvAlG', '', '2025-07-09 18:35:52', '2025-07-03'),
(15, 'teste 000', 'Ajbloom@teste000.com', '$2y$10$1PXMPzZSKCNV.E9tq8unseraGsolWmS6x0J/Awr3pHJ7LF7Q8H//6', '1111111111111', '2025-07-09 21:47:13', '2000-02-20');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `enderecos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
