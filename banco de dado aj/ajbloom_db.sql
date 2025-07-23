-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/07/2025 às 19:29
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
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `preco_antigo` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `imagem` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `lancamento` tinyint(1) DEFAULT NULL,
  `mais_vendido` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `preco_antigo`, `categoria`, `imagem`, `descricao`, `lancamento`, `mais_vendido`) VALUES
(1, 'Camiseta Floral', 79.90, 99.90, 'camisetas', 'https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop', '✓ Tecido 100% algodão<br>✓ Estampa floral exclusiva<br>✓ Ideal para dias quentes', NULL, NULL),
(2, 'Vestido Casual', 129.90, NULL, 'vestidos', 'https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop', '✓ Tecido leve<br>✓ Ideal para eventos e dias quentes', NULL, NULL),
(3, 'Brincos Delicados', 49.90, 59.90, 'acessorios', 'https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop', '✓ Acabamento delicado<br>✓ Perfeito para looks leves', NULL, NULL),
(4, 'Conjunto', 69.90, 89.90, 'camisetas', 'https://plus.unsplash.com/premium_photo-1723553201287-ab9a8fbe57d1?q=80&w=774&auto=format&fit=crop', '✓ Conjunto confortável<br>✓ Ideal para dia a dia', NULL, NULL),
(10, 'Vestido Floral Curto', 129.90, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Calça Wide Leg Jeans', 159.90, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
