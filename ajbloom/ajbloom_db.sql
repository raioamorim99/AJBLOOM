-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/07/2025 às 16:00
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

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `preco_antigo` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `imagem` text DEFAULT NULL,
  `imagem2` text DEFAULT NULL,
  `imagem3` text DEFAULT NULL,
  `imagem4` text DEFAULT NULL,
  `imagem5` text DEFAULT NULL,
  `imagem6` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `tamanhos` text DEFAULT NULL,
  `cores` text DEFAULT NULL,
  `lancamento` tinyint(1) DEFAULT 0,
  `mais_vendido` tinyint(1) DEFAULT 0,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `preco_antigo`, `categoria`, `imagem`, `imagem2`, `imagem3`, `imagem4`, `imagem5`, `imagem6`, `descricao`, `tamanhos`, `cores`, `lancamento`, `mais_vendido`) VALUES
(1, 'Camiseta Floral', 79.90, 99.90, 'camisetas', 'https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop', 'https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop', NULL, NULL, NULL, NULL, '✓ Tecido 100% algodão<br>✓ Estampa floral exclusiva<br>✓ Ideal para dias quentes', 'PP,P,M,G,GG', 'Branco,Rosa,Verde', 1, 0),
(2, 'Vestido Casual', 129.90, NULL, 'vestidos', 'https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?w=400&auto=format&fit=crop', 'https://images.unsplash.com/photo-1733395700989-febbc2d31ed8?q=80&w=774&auto=format&fit=crop', NULL, NULL, NULL, NULL, '✓ Tecido leve<br>✓ Ideal para eventos e dias quentes<br>✓ Modelagem feminina', 'P,M,G,GG', 'Azul,Preto,Vermelho', 0, 1),
(3, 'Brincos Delicados', 49.90, 59.90, 'acessorios', 'https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=774&auto=format&fit=crop', NULL, NULL, NULL, NULL, NULL, '✓ Acabamento delicado<br>✓ Perfeito para looks leves<br>✓ Material hipoalergênico', 'Único', 'Dourado,Prateado', 0, 0),
(4, 'Conjunto Confortável', 69.90, 89.90, 'camisetas', 'https://plus.unsplash.com/premium_photo-1723553201287-ab9a8fbe57d1?q=80&w=774&auto=format&fit=crop', NULL, NULL, NULL, NULL, NULL, '✓ Conjunto confortável<br>✓ Ideal para dia a dia<br>✓ Tecido respirável', 'PP,P,M,G', 'Cinza,Preto,Branco', 0, 1),
(5, 'Vestido Floral Curto', 129.90, NULL, 'vestidos', 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400&auto=format&fit=crop', NULL, NULL, NULL, NULL, NULL, 'Vestido floral perfeito para o verão', 'P,M,G', 'Floral,Rosa', 1, 0),
(6, 'Calça Wide Leg Jeans', 159.90, NULL, 'calcas', 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400&auto=format&fit=crop', NULL, NULL, NULL, NULL, NULL, 'Calça jeans moderna com modelagem wide leg', 'P,M,G,GG', 'Azul,Preto', 0, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_lancamento` (`lancamento`),
  ADD KEY `idx_mais_vendido` (`mais_vendido`),
  ADD KEY `idx_data_criacao` (`data_criacao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
