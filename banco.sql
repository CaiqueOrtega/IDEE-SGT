-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17-Abr-2024 às 19:35
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

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
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `id_funcionario_fk` int(11) UNSIGNED NOT NULL,
  `turma_aluno_fk` int(10) UNSIGNED NOT NULL,
  `nota` decimal(10,2) DEFAULT NULL,
  `frequencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`id`, `id_funcionario_fk`, `turma_aluno_fk`, `nota`, `frequencia`) VALUES
(40, 79, 65, NULL, 100),
(41, 80, 65, NULL, 100),
(42, 81, 65, NULL, 100);

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaborador`
--

CREATE TABLE `colaborador` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_usuario` int(11) UNSIGNED NOT NULL,
  `registro_conselho` int(11) NOT NULL,
  `cargo` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa_cliente`
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
-- Extraindo dados da tabela `empresa_cliente`
--

INSERT INTO `empresa_cliente` (`id`, `razao_social`, `nome_fantasia`, `email`, `cnpj`, `telefone`, `usuario_id`) VALUES
(117, 'Mpl industria e comercio de roupas ltda', 'Paco nine dado e m pollo', 'larissaqueiroz@gestaoapice.com.br', '08.007.677/0004-06', '(62) 3277-9900', 44),
(118, 'Ocw saude e seguranca do trabalho ltda', 'Idee saude e seguranca do trabalho', 'ortegaengseg@outlook.com', '45.221.565/0001-37', '(44) 9914-7571', 45),
(119, 'Textil canatiba ltda', 'Canatiba', 'canatiba@canatiba.com', '56.723.091/0001-48', '(19) 3459-4000', 44),
(120, 'Sancris linhas e fios ltda', 'Sancris linhas e fios ltda', 'siscon@siscon.cnt.br', '80.446.990/0010-16', '(44) 3024-9373', 44),
(121, 'Honda automoveis do brasil ltda', 'Honda', 'hab_fiscal@honda.com.br', '01.192.333/0001-22', '(19) 3864-4400', 44);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa_cliente_cargo`
--

CREATE TABLE `empresa_cliente_cargo` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `empresa_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `empresa_cliente_cargo`
--

INSERT INTO `empresa_cliente_cargo` (`id`, `nome`, `empresa_id`) VALUES
(129, 'Teste', 117),
(130, 'qteste', 118),
(131, 'teste ', 119),
(132, 'Gestor de Compras ', 120),
(133, 'vendedor', 121);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa_cliente_departamento`
--

CREATE TABLE `empresa_cliente_departamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `empresa_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `empresa_cliente_departamento`
--

INSERT INTO `empresa_cliente_departamento` (`id`, `nome`, `empresa_id`) VALUES
(102, 'Rh', 117),
(103, 'teste', 118),
(104, 'compras', 119),
(105, 'Compras', 120),
(106, 'vendas', 121);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa_cliente_funcionario`
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
-- Extraindo dados da tabela `empresa_cliente_funcionario`
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
-- Estrutura da tabela `ficha_inscricao`
--

CREATE TABLE `ficha_inscricao` (
  `id` int(10) UNSIGNED NOT NULL,
  `funcionarios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `treinamento_id` int(11) UNSIGNED NOT NULL,
  `empresa_id` int(10) UNSIGNED NOT NULL,
  `data_realizacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ficha_inscricao`
--

INSERT INTO `ficha_inscricao` (`id`, `funcionarios`, `treinamento_id`, `empresa_id`, `data_realizacao`) VALUES
(237, '[{\"id\":\"82\"}]', 24, 119, '2024-04-13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(256) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `permissao_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `login`
--

INSERT INTO `login` (`id`, `nome`, `email`, `senha`, `permissao_id`) VALUES
(44, 'Admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 4),
(45, 'Marcos Risson', 'darcos077@gmail.com', 'a43c27c2babefd68df8a694900f30a1c', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

CREATE TABLE `permissao` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `permissao`
--

INSERT INTO `permissao` (`id`, `nome`) VALUES
(1, 'admin'),
(2, 'colaborador'),
(3, 'cliente'),
(4, 'adminSuper');

-- --------------------------------------------------------

--
-- Estrutura da tabela `treinamento`
--

CREATE TABLE `treinamento` (
  `id` int(11) UNSIGNED NOT NULL,
  `colaborador_id` int(11) UNSIGNED NOT NULL,
  `nomenclatura` varchar(255) NOT NULL,
  `objetivo` varchar(500) NOT NULL,
  `carga_horaria` time NOT NULL,
  `horas_pratica` time NOT NULL,
  `horas_teorica` time NOT NULL,
  `ementa` varchar(500) DEFAULT NULL,
  `pre_requisitos` varchar(255) NOT NULL,
  `normas_referencia` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `reciclagem` varchar(20) NOT NULL,
  `nr` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `treinamento`
--

INSERT INTO `treinamento` (`id`, `colaborador_id`, `nomenclatura`, `objetivo`, `carga_horaria`, `horas_pratica`, `horas_teorica`, `ementa`, `pre_requisitos`, `normas_referencia`, `material`, `reciclagem`, `nr`) VALUES
(24, 45, 'Teste', 'Teste', '55:00:00', '25:00:00', '30:00:00', 'Teste', 'Teste', 'teste', 'Teste', 'Anual', '1'),
(25, 45, 'Treino de fotos grandes', 'Trinos saborosos', '50:00:00', '25:00:00', '25:00:00', 'Google', 'Buscar', 'louco', 'Hihihihhihi', 'Anual', '2'),
(26, 45, 'Treino', 'Treino', '65:00:00', '35:00:00', '30:00:00', 'Treino', 'Treino', ' treino ', 'Treino', 'Anual', '2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `id` int(10) UNSIGNED NOT NULL,
  `treinamento_id` int(11) UNSIGNED NOT NULL,
  `empresa_aluno` int(11) NOT NULL,
  `colaborador_id_fk` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`id`, `treinamento_id`, `empresa_aluno`, `colaborador_id_fk`) VALUES
(65, 24, 118, 45);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) UNSIGNED NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` char(14) NOT NULL,
  `telefone` char(16) NOT NULL,
  `genero` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `data_nascimento`, `cpf`, `telefone`, `genero`) VALUES
(44, '2004-11-04', '094.784.949-10', '(44) 9-9969-6842', 'M'),
(45, '2004-11-04', '452.599.192-54', '(44) 9-9969-6842', 'M');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_id_aluno_fk` (`turma_aluno_fk`),
  ADD KEY `funcionario_id_fk` (`id_funcionario_fk`);

--
-- Índices para tabela `colaborador`
--
ALTER TABLE `colaborador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id_colaborador_fk` (`id_usuario`);

--
-- Índices para tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id_fk` (`usuario_id`),
  ADD KEY `idx_empresa_cliente_id` (`id`);

--
-- Índices para tabela `empresa_cliente_cargo`
--
ALTER TABLE `empresa_cliente_cargo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_cargo_fk` (`empresa_id`);

--
-- Índices para tabela `empresa_cliente_departamento`
--
ALTER TABLE `empresa_cliente_departamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_cliente_departamento_id_fk` (`empresa_id`);

--
-- Índices para tabela `empresa_cliente_funcionario`
--
ALTER TABLE `empresa_cliente_funcionario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_funcionario_fk` (`empresa_id`) USING BTREE,
  ADD KEY `cargo_id_fk` (`cargo_id`),
  ADD KEY `departamento_id_fk` (`departamento_id`);

--
-- Índices para tabela `ficha_inscricao`
--
ALTER TABLE `ficha_inscricao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id_inscricao_fk` (`empresa_id`),
  ADD KEY `treinamento_id_inscricao_fk` (`treinamento_id`);

--
-- Índices para tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissao_id_fk` (`permissao_id`);

--
-- Índices para tabela `permissao`
--
ALTER TABLE `permissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `treinamento`
--
ALTER TABLE `treinamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissao_colaborador_fk` (`colaborador_id`);

--
-- Índices para tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treinamento_id_turma_fk` (`treinamento_id`),
  ADD KEY `id_colaborador_fk` (`colaborador_id_fk`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `colaborador`
--
ALTER TABLE `colaborador`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT de tabela `permissao`
--
ALTER TABLE `permissao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `treinamento`
--
ALTER TABLE `treinamento`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `funcionario_id_fk` FOREIGN KEY (`id_funcionario_fk`) REFERENCES `empresa_cliente_funcionario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turma_id_aluno_fk` FOREIGN KEY (`turma_aluno_fk`) REFERENCES `turma` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `colaborador`
--
ALTER TABLE `colaborador`
  ADD CONSTRAINT `usuario_id_colaborador_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD CONSTRAINT `usuario_id_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `empresa_cliente_cargo`
--
ALTER TABLE `empresa_cliente_cargo`
  ADD CONSTRAINT `empresa_id_cargo_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Limitadores para a tabela `empresa_cliente_departamento`
--
ALTER TABLE `empresa_cliente_departamento`
  ADD CONSTRAINT `empresa_cliente_departamento_id_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Limitadores para a tabela `empresa_cliente_funcionario`
--
ALTER TABLE `empresa_cliente_funcionario`
  ADD CONSTRAINT `cargo_id_fk` FOREIGN KEY (`cargo_id`) REFERENCES `empresa_cliente_cargo` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `departamento_id_fk` FOREIGN KEY (`departamento_id`) REFERENCES `empresa_cliente_departamento` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `empresa_id_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`);

--
-- Limitadores para a tabela `ficha_inscricao`
--
ALTER TABLE `ficha_inscricao`
  ADD CONSTRAINT `empresa_id_inscricao_fk` FOREIGN KEY (`empresa_id`) REFERENCES `empresa_cliente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treinamento_id_inscricao_fk` FOREIGN KEY (`treinamento_id`) REFERENCES `treinamento` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `id_login_pfk` FOREIGN KEY (`id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `permissao_id_fk` FOREIGN KEY (`permissao_id`) REFERENCES `permissao` (`id`);

--
-- Limitadores para a tabela `treinamento`
--
ALTER TABLE `treinamento`
  ADD CONSTRAINT `permissao_colaborador_fk` FOREIGN KEY (`colaborador_id`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `id_colaborador_fk` FOREIGN KEY (`colaborador_id_fk`) REFERENCES `login` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treinamento_id_turma_fk` FOREIGN KEY (`treinamento_id`) REFERENCES `treinamento` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
