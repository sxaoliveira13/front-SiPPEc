-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 09:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sippec`
--

-- --------------------------------------------------------

--
-- Table structure for table `ability`
--

CREATE TABLE `ability` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ability`
--

INSERT INTO `ability` (`id`, `name`, `createdAt`) VALUES
(1, 'Pensamento lógico', '2024-11-02 14:39:47'),
(2, 'Criatividade', '2024-11-02 14:39:47'),
(3, 'Resolução de problemas', '2024-11-02 14:39:47'),
(4, 'Programação', '2024-11-02 14:39:47'),
(5, 'Não se aplica', '2024-11-02 14:39:47'),
(6, 'Raciocínio lógico', '2024-11-02 14:39:47'),
(7, 'Colaboração', '2024-11-02 14:39:47'),
(8, 'Conhecimento computacional', '2024-11-02 14:39:47'),
(9, 'Lógica', '2024-11-02 14:39:47'),
(10, 'Sustentabilidade', '2024-11-02 14:39:47'),
(11, 'Abstração', '2024-11-02 14:39:47'),
(12, 'Resolução de problemas / Raciocínio Lógico / Criatividade / Colaboração / Programação / Lógica / Conhecimento computacional', '2024-11-02 14:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `actuser`
--

CREATE TABLE `actuser` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Usuário Comum não aprovado\r\n1 - Usuário Comum aprovado\r\n2 - Admin\r\n3 - Usuário Comum negado',
  `password` varchar(128) NOT NULL,
  `loginAttempts` tinyint(4) NOT NULL DEFAULT 0,
  `isLocked` tinyint(1) NOT NULL DEFAULT 0,
  `unlockDate` datetime DEFAULT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp(),
  `lastAccess` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actusertoken`
--

CREATE TABLE `actusertoken` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `validity` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catalogo`
--

CREATE TABLE `catalogo` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `Ativo` int(11) NOT NULL DEFAULT 0,
  `Status` int(11) NOT NULL DEFAULT 1 COMMENT '1 - cadastro pendente 2 - cadastro aprovado 3 - cadastro recusado 4 - atualização pendente 5 - atualização aprovada 6 - atualização recusada 7 - exclusão pendente 8 - exclusão aprovada 9 - exclusão recusada',
  `AguardandoRevisao` tinyint(1) NOT NULL DEFAULT 1,
  `Ciclo` int(11) NOT NULL DEFAULT 0,
  `CategoriaId` int(11) NOT NULL,
  `Titulo` varchar(200) NOT NULL,
  `PublicoAlvoId` int(11) NOT NULL,
  `Conteudo` varchar(250) NOT NULL,
  `FerramentaId` int(11) NOT NULL,
  `HabilidadeId` int(11) NOT NULL,
  `Ambiente` varchar(70) DEFAULT NULL,
  `Abordagem` varchar(70) NOT NULL,
  `CaminhoDeAcesso` varchar(300) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `lastUpdate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catalogoatualizado`
--

CREATE TABLE `catalogoatualizado` (
  `id` int(11) NOT NULL,
  `CatalogoId` int(11) NOT NULL,
  `CategoriaId` int(11) NOT NULL,
  `Titulo` int(11) NOT NULL,
  `PublicoAlvoId` int(11) NOT NULL,
  `Conteudo` varchar(250) NOT NULL,
  `FerramentaId` int(11) NOT NULL,
  `HabilidadeId` int(11) NOT NULL,
  `Ambiente` varchar(70) DEFAULT NULL,
  `Abordagem` varchar(70) DEFAULT NULL,
  `CaminhoDeAcesso` varchar(250) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`id`, `name`, `createdAt`) VALUES
(1, 'Artigo', '2024-11-02 14:27:27'),
(2, 'Jogo', '2024-11-02 14:27:27'),
(3, 'Metodo', '2024-11-02 14:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `catalogId` int(11) NOT NULL,
  `ciclo` int(11) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passwordrecover`
--

CREATE TABLE `passwordrecover` (
  `id` int(11) NOT NULL,
  `userEmail` varchar(128) NOT NULL,
  `token` varchar(256) NOT NULL,
  `recoveryCode` varchar(6) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `expiresAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public`
--

CREATE TABLE `public` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `public`
--

INSERT INTO `public` (`id`, `name`, `createdAt`) VALUES
(1, 'Ensino Infantil', '2024-11-02 14:42:00'),
(2, 'Ensino Fundamental I', '2024-11-02 14:42:00'),
(3, 'Ensino Fundamental II', '2024-11-02 14:42:00'),
(4, 'Ensino Médio', '2024-11-02 14:42:00'),
(5, 'Formação para professores', '2024-11-02 14:42:00'),
(6, 'Educação Especial', '2024-11-02 14:42:00'),
(7, 'Ensino Técnico', '2024-11-02 14:42:00'),
(8, 'Ensino Superior', '2024-11-02 14:42:00'),
(9, 'Fundamental II / Formação para professores / Ensino Médio / Ensino Técnico / Ensino Superior', '2024-11-02 14:42:00'),
(10, 'Fundamental II / Ensino Médio / Ensino Técnico / Ensino Superior', '2024-11-02 14:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `tool`
--

CREATE TABLE `tool` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tool`
--

INSERT INTO `tool` (`id`, `name`, `createdAt`) VALUES
(1, 'GNU', '2024-11-02 14:45:20'),
(2, 'Estilo BSD', '2024-11-02 14:45:20'),
(3, 'Papel e lápis', '2024-11-02 14:45:20'),
(4, 'Scratch', '2024-11-02 14:45:20'),
(5, 'App Inventor', '2024-11-02 14:45:20'),
(6, 'Computação Física', '2024-11-02 14:45:20'),
(7, 'HTML', '2024-11-02 14:45:20'),
(8, 'HTM/CSS', '2024-11-02 14:45:20'),
(9, 'Python', '2024-11-02 14:45:20'),
(10, 'Robótica Educacional', '2024-11-02 14:45:20'),
(11, 'Programação', '2024-11-02 14:45:20'),
(12, 'MIT', '2024-11-02 14:45:20'),
(13, 'Apache', '2024-11-02 14:45:20'),
(14, 'WTFPL', '2024-11-02 14:45:20'),
(15, 'Geogebra', '2024-11-02 14:45:20'),
(16, 'D. Público', '2024-11-02 14:45:20'),
(17, 'Não se aplica', '2024-11-02 14:45:20'),
(18, 'Beer License', '2024-11-02 14:45:20'),
(19, 'MirOS', '2024-11-02 14:45:20'),
(20, 'ISC', '2024-11-02 14:45:20'),
(21, 'EPL', '2024-11-02 14:45:20'),
(22, 'XSkat', '2024-11-02 14:45:20'),
(23, 'Computação Física; ComFAPOO; Arduino; C++; Avaliações', '2024-11-02 14:45:20'),
(24, 'Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)', '2024-11-02 14:45:20'),
(25, 'Scratch; Code.Org; Era uma vez', '2024-11-02 14:45:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ability`
--
ALTER TABLE `ability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actuser`
--
ALTER TABLE `actuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actusertoken`
--
ALTER TABLE `actusertoken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actUserToken_fk_userId` (`userId`);

--
-- Indexes for table `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catalogo_fk_userId` (`userId`),
  ADD KEY `catalogo_fk_publicAlvoId` (`PublicoAlvoId`),
  ADD KEY `catalogo_fk_categoriaId` (`CategoriaId`),
  ADD KEY `catalogo_fk_ferramentaId` (`FerramentaId`),
  ADD KEY `catalogo_fk_habilidadeId` (`HabilidadeId`);

--
-- Indexes for table `catalogoatualizado`
--
ALTER TABLE `catalogoatualizado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catalogoAtualizado_fk_catalogoId` (`CatalogoId`),
  ADD KEY `catalogoAtualizado_fk_categoriaId` (`CategoriaId`),
  ADD KEY `catalogoAtualizado_fk_ferramentaId` (`FerramentaId`),
  ADD KEY `catalogoAtualizado_fk_habilidadeId` (`HabilidadeId`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_fk_catalogoId` (`catalogId`);

--
-- Indexes for table `passwordrecover`
--
ALTER TABLE `passwordrecover`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public`
--
ALTER TABLE `public`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tool`
--
ALTER TABLE `tool`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ability`
--
ALTER TABLE `ability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `actuser`
--
ALTER TABLE `actuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actusertoken`
--
ALTER TABLE `actusertoken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catalogoatualizado`
--
ALTER TABLE `catalogoatualizado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwordrecover`
--
ALTER TABLE `passwordrecover`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public`
--
ALTER TABLE `public`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tool`
--
ALTER TABLE `tool`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actusertoken`
--
ALTER TABLE `actusertoken`
  ADD CONSTRAINT `actUserToken_fk_userId` FOREIGN KEY (`userId`) REFERENCES `actuser` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `catalogo`
--
ALTER TABLE `catalogo`
  ADD CONSTRAINT `catalogo_fk_categoriaId` FOREIGN KEY (`CategoriaId`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogo_fk_ferramentaId` FOREIGN KEY (`FerramentaId`) REFERENCES `tool` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogo_fk_habilidadeId` FOREIGN KEY (`HabilidadeId`) REFERENCES `ability` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogo_fk_publicAlvoId` FOREIGN KEY (`PublicoAlvoId`) REFERENCES `public` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogo_fk_userId` FOREIGN KEY (`userId`) REFERENCES `actuser` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `catalogoatualizado`
--
ALTER TABLE `catalogoatualizado`
  ADD CONSTRAINT `catalogoAtualizado_fk_catalogoId` FOREIGN KEY (`CatalogoId`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogoAtualizado_fk_categoriaId` FOREIGN KEY (`CategoriaId`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogoAtualizado_fk_ferramentaId` FOREIGN KEY (`FerramentaId`) REFERENCES `tool` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalogoAtualizado_fk_habilidadeId` FOREIGN KEY (`HabilidadeId`) REFERENCES `ability` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_fk_catalogoId` FOREIGN KEY (`catalogId`) REFERENCES `catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
