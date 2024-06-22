-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/06/2024 às 05:33
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `id_funcionario_fk` int(11) UNSIGNED NOT NULL,
  `turma_aluno_fk` int(10) UNSIGNED NOT NULL,
  `nota_pratica` decimal(10,1) NOT NULL,
  `nota_teorica` decimal(10,1) NOT NULL,
  `nota_media` decimal(10,1) NOT NULL,
  `frequencia` decimal(10,1) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `id_funcionario_fk`, `turma_aluno_fk`, `nota_pratica`, `nota_teorica`, `nota_media`, `frequencia`, `status`) VALUES
(179, 78, 147, 10.0, 7.0, 7.9, 100.0, 'ativo'),
(180, 84, 147, 10.0, 10.0, 10.0, 100.0, 'ativo'),
(184, 85, 149, 7.0, 10.0, 9.1, 75.0, 'inativo'),
(186, 79, 151, 0.0, 10.0, 7.0, 53.3, 'ativo'),
(187, 80, 151, 6.3, 10.0, 8.9, 100.0, 'ativo'),
(188, 81, 151, 0.0, 0.0, 0.0, 100.0, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente`
--

CREATE TABLE `empresa_cliente` (
  `id` int(11) UNSIGNED NOT NULL,
  `razao_social` varchar(100) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_cliente`
--

INSERT INTO `empresa_cliente` (`id`, `razao_social`, `nome_fantasia`, `email`, `cnpj`, `telefone`, `usuario_id`) VALUES
(117, 'Mpl industria e comercio de roupas ltda', 'Paco nine dado', 'klarissaqueiroz@gestaoapice.com.br', '08.007.677/0004-06', '(62) 3277-9900', 44),
(118, 'Ocw saude e seguranca do trabalho ltda', 'Idee saude e seguranca do trabalho', 'ortegaengseg@outlook.com', '45.221.565/0001-37', '(44) 9914-7571', 45),
(119, 'Textil canatiba ltda', 'Canatiba1', 'canatiba@canatiba.com', '56.723.091/0001-48', '(19) 3459-4000', 44),
(120, 'Sancris linhas e fios ltda', 'Sancris linhas e fios ltda', 'siscon@siscon.cnt.br', '80.446.990/0010-16', '(44) 3024-9373', 44),
(121, 'Honda automoveis do brasil ltda', 'Honda', 'hab_fiscal@honda.com.br', '01.192.333/0001-22', '(19) 3864-4400', 44);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente_cargo`
--

CREATE TABLE `empresa_cliente_cargo` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `empresa_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_cliente_cargo`
--

INSERT INTO `empresa_cliente_cargo` (`id`, `nome`, `empresa_id`) VALUES
(129, 'Teste', 117),
(130, 'qteste', 118),
(131, 'teste ', 119),
(132, 'Gestor de Compras ', 120),
(133, 'vendedor', 121);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente_departamento`
--

CREATE TABLE `empresa_cliente_departamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `empresa_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_cliente_departamento`
--

INSERT INTO `empresa_cliente_departamento` (`id`, `nome`, `empresa_id`) VALUES
(102, 'Rh', 117),
(103, 'teste', 118),
(104, 'compras', 119),
(105, 'Compras', 120),
(106, 'vendas', 121);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente_funcionario`
--

CREATE TABLE `empresa_cliente_funcionario` (
  `id` int(11) UNSIGNED NOT NULL,
  `empresa_id` int(11) UNSIGNED NOT NULL,
  `nome_funcionario` varchar(100) NOT NULL,
  `email` varchar(256) NOT NULL,
  `telefone` varchar(16) NOT NULL,
  `genero` char(1) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `numero_registro_empresa` int(11) NOT NULL,
  `cargo_id` int(11) UNSIGNED DEFAULT NULL,
  `departamento_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_cliente_funcionario`
--

INSERT INTO `empresa_cliente_funcionario` (`id`, `empresa_id`, `nome_funcionario`, `email`, `telefone`, `genero`, `cpf`, `numero_registro_empresa`, `cargo_id`, `departamento_id`) VALUES
(78, 117, 'Marcos Risson', 'marcos@marcos.com', '(44) 9-9969-6842', 'M', '094.784.949-10', 1, 129, 102),
(79, 118, 'teste', 'marcos@marcos.com', '(44) 9-9969-6842', 'F', '182.311.304-46', 2, 130, 103),
(80, 118, 'caique', 'caique@caique.com', '(44) 9-9969-6842', 'F', '940.873.387-89', 3, 130, 103),
(81, 118, 'jao ', 'joao@joao.com', '(32) 7-7990-0652', 'M', '603.683.582-59', 4, 130, 103),
(82, 119, 'Joao zoi', 'jao@jao.com', '(36) 4-3535-2865', 'M', '665.754.742-09', 3, 131, 104),
(83, 120, 'marcia', 'marcia@marcia.com', '(44) 9-5652-9652', 'F', '932.656.220-45', 1, 132, 105),
(84, 117, 'joao paulo ', 'paulo@joao.com', '(65) 2-3256-2520', 'M', '733.618.760-95', 2, 129, 102),
(85, 121, 'Guilherme', 'guilherme@guilherme.com', '(65) 2-4541-5623', 'M', '203.152.830-09', 1, 133, 106);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ficha_inscricao`
--

CREATE TABLE `ficha_inscricao` (
  `id` int(10) UNSIGNED NOT NULL,
  `funcionarios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `treinamento_id` int(11) UNSIGNED NOT NULL,
  `empresa_id` int(10) UNSIGNED NOT NULL,
  `data_realizacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_inscricao`
--

INSERT INTO `ficha_inscricao` (`id`, `funcionarios`, `treinamento_id`, `empresa_id`, `data_realizacao`) VALUES
(337, '[{\"id\":\"85\"}]', 27, 121, '2024-06-15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `frequencia_aluno`
--

CREATE TABLE `frequencia_aluno` (
  `id` int(11) NOT NULL,
  `aluno_id_fk` int(11) NOT NULL,
  `turma_id_fk` int(10) UNSIGNED NOT NULL,
  `dia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `frequencia_aluno`
--

INSERT INTO `frequencia_aluno` (`id`, `aluno_id_fk`, `turma_id_fk`, `dia`) VALUES
(176, 184, 149, 4),
(224, 186, 151, 1),
(225, 186, 151, 2),
(226, 186, 151, 3),
(227, 186, 151, 4),
(228, 186, 151, 5),
(229, 186, 151, 6),
(230, 186, 151, 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(256) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `permissao_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `login`
--

INSERT INTO `login` (`id`, `nome`, `email`, `senha`, `permissao_id`) VALUES
(44, 'Admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 4),
(45, 'Marcos Risson', 'darcos077@gmail.com', '1791962eadeadcd9001ce88815698370', 2),
(46, 'Caique Ortega ', 'caique@caique.com', 'b9bb2af1b75e826fb82cedabd4f3fa8b', 2),
(47, 'Geovana Pavesi', 'geovana@geovana.com', 'b9bb2af1b75e826fb82cedabd4f3fa8b', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissao`
--

CREATE TABLE `permissao` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `permissao`
--

INSERT INTO `permissao` (`id`, `nome`) VALUES
(1, 'admin'),
(2, 'colaborador'),
(3, 'cliente'),
(4, 'adminSuper');

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinamento`
--

CREATE TABLE `treinamento` (
  `id` int(11) UNSIGNED NOT NULL,
  `colaborador_id` int(11) UNSIGNED NOT NULL,
  `nomenclatura` varchar(255) NOT NULL,
  `objetivo` varchar(500) NOT NULL,
  `carga_horaria` int(11) NOT NULL,
  `horas_pratica` int(11) NOT NULL,
  `horas_teorica` int(11) NOT NULL,
  `ementa` varchar(500) DEFAULT NULL,
  `pre_requisitos` varchar(255) NOT NULL,
  `normas_referencia` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `reciclagem` varchar(20) NOT NULL,
  `nr` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treinamento`
--

INSERT INTO `treinamento` (`id`, `colaborador_id`, `nomenclatura`, `objetivo`, `carga_horaria`, `horas_pratica`, `horas_teorica`, `ementa`, `pre_requisitos`, `normas_referencia`, `material`, `reciclagem`, `nr`) VALUES
(24, 45, 'Teste', 'Teste', 60, 30, 30, 'Teste', 'Teste', 'teste', 'Teste', 'Anual', '1'),
(27, 46, 'Treinamento1', 'Caique', 16, 8, 8, 'Caique', 'Caique', 'caique ', 'Caique', 'Bianual', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turma`
--

CREATE TABLE `turma` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome_turma` varchar(255) NOT NULL,
  `treinamento_id` int(11) UNSIGNED NOT NULL,
  `empresa_aluno` int(11) UNSIGNED NOT NULL,
  `colaborador_id_fk` int(10) UNSIGNED NOT NULL,
  `data_inicio` date NOT NULL,
  `data_conclusao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turma`
--

INSERT INTO `turma` (`id`, `nome_turma`, `treinamento_id`, `empresa_aluno`, `colaborador_id_fk`, `data_inicio`, `data_conclusao`) VALUES
(147, 'Turma D', 27, 117, 45, '2024-06-15', '2024-06-22'),
(149, 'Turma F', 27, 121, 46, '2024-06-15', '2024-06-19'),
(151, 'Turma H', 24, 118, 45, '2024-06-20', '2024-06-22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) UNSIGNED NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` char(14) NOT NULL,
  `telefone` char(16) NOT NULL,
  `genero` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `data_nascimento`, `cpf`, `telefone`, `genero`) VALUES
(44, '2004-11-04', '094.784.949-10', '(44) 9-9969-6842', 'M'),
(45, '2004-11-04', '452.599.192-54', '(44) 9-9969-6842', 'M'),
(46, '2004-11-07', '697.924.960-68', '(85) 4-8652-4185', 'M'),
(47, '1998-07-05', '827.250.330-12', '(65) 3-4165-3241', 'F');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_id_fk` (`id_funcionario_fk`),
  ADD KEY `turma_id_aluno_fk` (`turma_aluno_fk`);

--
-- Índices de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id_fk` (`usuario_id`),
  ADD KEY `idx_empresa_cliente_id` (`id`);

--
-- Índices de tabela `empresa_cliente_cargo`
--
ALTER TABLE `empresa_cliente_cargo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_cargo_fk` (`empresa_id`);

--
-- Índices de tabela `empresa_cliente_departamento`
--
ALTER TABLE `empresa_cliente_departamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_cliente_departamento_id_fk` (`empresa_id`);

--
-- Índices de tabela `empresa_cliente_funcionario`
--
ALTER TABLE `empresa_cliente_funcionario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_funcionario_fk` (`empresa_id`) USING BTREE,
  ADD KEY `cargo_id_fk` (`cargo_id`),
  ADD KEY `departamento_id_fk` (`departamento_id`);

--
-- Índices de tabela `ficha_inscricao`
--
ALTER TABLE `ficha_inscricao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_inscricao_fk` (`empresa_id`),
  ADD KEY `treinamento_id_inscricao_fk` (`treinamento_id`);

--
-- Índices de tabela `frequencia_aluno`
--
ALTER TABLE `frequencia_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `frequencia_turma_id_fk` (`turma_id_fk`),
  ADD KEY `frequencia_aluno_id_fk` (`aluno_id_fk`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissao_id_fk` (`permissao_id`);

--
-- Índices de tabela `permissao`
--
ALTER TABLE `permissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `treinamento`
--
ALTER TABLE `treinamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissao_colaborador_fk` (`colaborador_id`);

--
-- Índices de tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treinamento_id_turma_fk` (`treinamento_id`),
  ADD KEY `id_colaborador_fk` (`colaborador_id_fk`),
  ADD KEY `empresa_id_turma_fk` (`empresa_aluno`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de tabela `empresa_cliente_cargo`
--
ALTER TABLE `empresa_cliente_cargo`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de tabela `empresa_cliente_departamento`
--
ALTER TABLE `empresa_cliente_departamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de tabela `empresa_cliente_funcionario`
--
ALTER TABLE `empresa_cliente_funcionario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de tabela `ficha_inscricao`
--
ALTER TABLE `ficha_inscricao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT de tabela `frequencia_aluno`
--
ALTER TABLE `frequencia_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT de tabela `permissao`
--
ALTER TABLE `permissao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `treinamento`
--
ALTER TABLE `treinamento`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `funcionario_id_fk` FOREIGN KEY (`id_funcionario_fk`) REFERENCES `empresa_cliente_funcionario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turma_id_aluno_fk` FOREIGN KEY (`turma_aluno_fk`) REFERENCES `turma` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD CONSTRAINT `usuario_id_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `empresa_cliente_cargo`
--
ALTER TABLE `empresa_cliente_cargo`
  ADD CONSTRAINT `empresa_id_cargo_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Restrições para tabelas `empresa_cliente_departamento`
--
ALTER TABLE `empresa_cliente_departamento`
  ADD CONSTRAINT `empresa_cliente_departamento_id_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Restrições para tabelas `empresa_cliente_funcionario`
--
ALTER TABLE `empresa_cliente_funcionario`
  ADD CONSTRAINT `cargo_id_fk` FOREIGN KEY (`cargo_id`) REFERENCES `empresa_cliente_cargo` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `departamento_id_fk` FOREIGN KEY (`departamento_id`) REFERENCES `empresa_cliente_departamento` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `empresa_id_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Restrições para tabelas `ficha_inscricao`
--
ALTER TABLE `ficha_inscricao`
  ADD CONSTRAINT `empresa_id_inscricao_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treinamento_id_inscricao_fk` FOREIGN KEY (`treinamento_id`) REFERENCES `treinamento` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `frequencia_aluno`
--
ALTER TABLE `frequencia_aluno`
  ADD CONSTRAINT `frequencia_aluno_id_fk` FOREIGN KEY (`aluno_id_fk`) REFERENCES `aluno` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencia_turma_id_fk` FOREIGN KEY (`turma_id_fk`) REFERENCES `turma` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `id_login_pfk` FOREIGN KEY (`id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `permissao_id_fk` FOREIGN KEY (`permissao_id`) REFERENCES `permissao` (`id`);

--
-- Restrições para tabelas `treinamento`
--
ALTER TABLE `treinamento`
  ADD CONSTRAINT `permissao_colaborador_fk` FOREIGN KEY (`colaborador_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `empresa_id_turma_fk` FOREIGN KEY (`empresa_aluno`) REFERENCES `empresa_cliente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `id_colaborador_fk` FOREIGN KEY (`colaborador_id_fk`) REFERENCES `login` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treinamento_id_turma_fk` FOREIGN KEY (`treinamento_id`) REFERENCES `treinamento` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
