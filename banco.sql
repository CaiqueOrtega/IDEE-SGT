

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; 

START TRANSACTION; 

SET time_zone = "+00:00"; 

 

 


 

CREATE TABLE colaborador (
  id int(11) UNSIGNED NOT NULL,
  id_usuario int(11) UNSIGNED NOT NULL,
  registro_conselho int(11) NOT NULL,
  cargo varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 

CREATE TABLE `empresa_cliente` ( 

  `id` int(11) UNSIGNED NOT NULL, 

  `razao_social` varchar(100) NOT NULL, 

  `nome_fantasia` varchar(255) NOT NULL, 

  `email` varchar(255) NOT NULL, 

  `cnpj` varchar(18) NOT NULL, 

  `telefone` varchar(20) NOT NULL, 

  `usuario_id` int(10) UNSIGNED NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 



INSERT INTO `empresa_cliente` (`id`, `razao_social`, `nome_fantasia`, `email`, `cnpj`, `telefone`, `usuario_id`) VALUES 

(109, 'Idee', 'Idee saude e seguranca do trabalho', 'ortegaengseg@outlook.com', '45.221.565/0001-37', '(11) 1-1111-1111', 22), 

(111, 'Mpl industria e comercio de roupas ltda', 'M pollo e paco', 'williangonzaga@gestaoapice.com.br', '08.007.677/0001-63', '(62) 3277-9900', 23), 

(112, 'Rm comercio de embalagens ltda', 'Rm embalagens', 'ramosfilmestretch@outlook.com', '45.221.569/0001-15', '(11) 4648-8767', 23), 

(113, '45221568 marielli wada queiroz', 'Marielli wada queiroz', 'marielli.mary@hotmail.com', '45.221.568/0001-70', '(16) 9209-4499', 23), 

(114, 'Simone do carmo figueredo 06953076739', 'Ms bijusss', 'symone_figueredo@yahoo.com.br', '45.221.564/0001-92', '(21) 2629-6510', 23), 

(115, 'Mpl e comercio de roupas ltda', 'Paco nine dado e m pollo', 'larissaqueiroz@gestaoapice.com.br', '08.007.677/0004-06', '(62) 3277-9900', 24); 

 

 

CREATE TABLE `empresa_cliente_cargo` ( 

  `id` int(11) UNSIGNED NOT NULL, 

  `nome` varchar(255) NOT NULL, 

  `empresa_id` int(11) UNSIGNED NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 



INSERT INTO `empresa_cliente_cargo` (`id`, `nome`, `empresa_id`) VALUES 

(105, 'testeIdee', 109), 

(107, 'testaIdee', 109), 

(108, 'testoIdee', 109), 

(109, 'testeMpollo', 111), 

(111, 'testeRm', 112), 

(112, 'testeMarielli', 113), 

(113, 'testeMs', 114), 

(114, 'testaMpollo', 111), 

(115, 'testaRm', 112), 

(116, 'testaMarielli', 113), 

(117, 'testaMs', 114), 

(118, 'testoMpollo', 111), 

(119, 'testoRm', 112), 

(120, 'testoMarielli', 113), 

(121, 'testoMs', 114), 

(122, 'testandoRm', 112), 

(123, 'testandoMs', 114), 

(124, 'teste', 115), 

(125, 'testa', 115), 

(126, 'testo', 115); 


 

CREATE TABLE `empresa_cliente_departamento` ( 

  `id` int(10) UNSIGNED NOT NULL, 

  `nome` varchar(255) NOT NULL, 

  `empresa_id` int(11) UNSIGNED NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 



INSERT INTO `empresa_cliente_departamento` (`id`, `nome`, `empresa_id`) VALUES 

(78, 'testeIdee', 109), 

(82, 'testeMpollo', 111), 

(84, 'testeRm', 112), 

(85, 'testeMarielli', 113), 

(86, 'testeMs', 114), 

(87, 'testaMpollo', 111), 

(88, 'testaRm', 112), 

(89, 'testaMarielli', 113), 

(90, 'testaMs', 114), 

(91, 'testoMpollo', 111), 

(92, 'testoRm', 112), 

(93, 'testoMarielli', 113), 

(94, 'testoMs', 114), 

(95, 'testandoMpollo', 111), 

(96, 'testaIdee', 109), 

(97, 'teste', 115), 

(98, 'testa', 115), 

(99, 'testo', 115); 

 


 

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

 


INSERT INTO `empresa_cliente_funcionario` (`id`, `empresa_id`, `nome_funcionario`, `email`, `telefone`, `genero`, `cpf`, `numero_registro_empresa`, `cargo_id`, `departamento_id`) VALUES 

(65, 109, 'Caique Ortega', 'caique@gmail.com', '(44) 9-8828-7181', 'M', '106.238.579-95', 111, 105, 78), 

(68, 111, 'Joao Paulo', 'jao@gmail.com', '(44) 9-8841-1717', 'M', '122.030.639-80', 1, 109, 82), 

(69, 111, 'Marcos Paulo', 'marcos@gmail.com', '(44) 9-8876-4453', 'M', '122.030.639-80', 2, 109, 82), 

(71, 113, 'Wanessa Cristiani', 'wanessa@gmail.com', '(44) 9-8841-1717', 'F', '060.555.269-05', 1, 112, 85), 

(72, 112, 'Cristiano Ortega', 'ortega@gmail.com', '(44) 9-9914-7571', 'M', '028.581.539-30', 1, 111, 84), 

(73, 114, 'Geovana Pavezi', 'geovana@gmail.com', '(44) 9-8819-1766', 'F', '117.857.129-77', 1, 113, 86), 

(74, 109, 'teste', 'teste@gmail.com', '(44) 9-9176-5411', 'M', '427.277.070-53', 112, 105, 96), 

(75, 115, 'Joao testo', 'joaotesto@gmail.com', '(44) 9-6781-1717', 'M', '845.968.460-14', 111, 126, 97), 

(76, 115, 'Marcos testo', 'marcostesto@gmail.com', '(44) 9-9878-7887', 'M', '615.967.270-39', 112, 126, 97); 

 


 

CREATE TABLE `ficha_inscricao` ( 

  `id` int(10) UNSIGNED NOT NULL, 

  `funcionarios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL, 

  `treinamento_id` int(11) UNSIGNED NOT NULL, 

  `empresa_id` int(10) UNSIGNED NOT NULL, 

  `data_realizacao` date NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

 



INSERT INTO `ficha_inscricao` (`id`, `funcionarios`, `treinamento_id`, `empresa_id`, `data_realizacao`) VALUES 

(217, '[{\"id\":\"65\"}]', 1, 109, '2023-12-07'), 

(219, '[{\"id\":\"65\"},{\"id\":\"74\"}]', 3, 109, '2023-12-12'), 

(221, '[{\"id\":\"75\"}]', 1, 115, '2023-12-13'); 

 


CREATE TABLE `funcionarios_da_empresa_do_usuario` ( 

); 

 


 

CREATE TABLE `login` ( 

  `id` int(11) UNSIGNED NOT NULL, 

  `nome` varchar(255) NOT NULL, 

  `email` varchar(256) NOT NULL, 

  `senha` varchar(255) NOT NULL, 

  `permissao_id` int(10) UNSIGNED NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

 


INSERT INTO `login` (`id`, `nome`, `email`, `senha`, `permissao_id`) VALUES 

(19, 'Caique Ortega', 'caiqueortega@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 4), 

(20, 'Geovana', 'geovana@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 1), 

(21, 'Wanessa Cristiani Lorenzoni', 'wanessa@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 3), 

(22, 'Cristiano Ortega', 'ortega@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 2), 

(23, 'Jao', 'jao@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 3), 

(24, 'Marcos Ortega Paulo ', 'marcos@gmail.com', '1f29a60da38fc4bc7c159c0d78e11ef6', 3); 

 


 

CREATE TABLE `permissao` ( 

  `id` int(10) UNSIGNED NOT NULL, 

  `nome` varchar(255) NOT NULL 

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

 


 

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

-- Despejando dados para a tabela `treinamento` 

-- 

 

INSERT INTO `treinamento` (`id`, `colaborador_id`, `nomenclatura`, `objetivo`, `carga_horaria`, `horas_pratica`, `horas_teorica`, `ementa`, `pre_requisitos`, `normas_referencia`, `material`, `reciclagem`, `nr`) VALUES 

(1, 22, 'Curso de segurança e saúde no manuseio e trabalho com agrotóxicos aditivos adjuvantes e produtos afins de forma direta e indireta', 'Capacitar o trabalhador no manuseio e aplicaço de defensivos agrícolas observando normas e procedimentos técnicos de segurança qualidade higiene saúde e preservaço ambiental', '20:00:00', '10:00:00', '10:00:00', 'Teste ementa', '18 anos conhecimentos equivalentes ao ensino fundamental completo', 'NR', 'Material didático', 'Anual', '31'), 

(3, 22, 'Curso de segurança na operação de carregador florestal ', 'Capacitar o trabalhador na área de transporte de cargas, de modo que operem de forma segura e eficiente carregador florestal (equipamento para movimentação de madeira), uniformizando procedimentos para inspeção, manutenção e conservação destes equipamento', '24:00:00', '12:00:00', '12:00:00', '1. Princípios de segurança na utilização dos equipamentos;  2. Descrição dos riscos relacionados aos equipamentos;  3. Centro de gravidade de cargas;  4. Amarração de cargas;  5. Escolha dos tipos de cabos de aço (estropos);  6. Capacidade de carga dos ca', '18 anos, conhecimentos equivalentes ao Ensino Fundamental completo.\r\n\r\n', '- NR11\r\n- NR12\r\n- NR31', '- Material didático\r\n- Equipamento da empresa\r\n- Rádio comunicador ', 'Anual', '31'), 

(17, 22, 'Testando nomenclatura', 'Testando objetivo', '20:00:00', '10:00:00', '10:00:00', 'Testando ementa', 'Testando prérequisitos', 'Testando normas referência', 'Testando material didático', 'Trianual', '32'), 

(22, 22, 'Teste nomenclatura testando', 'Teste objetivo testando', '30:00:00', '10:00:00', '20:00:00', 'Teste ementa testando', 'Teste prérequisitos testando', 'Teste normas referência testando', 'Teste material didático testando', 'Bianual', '36'); 

 

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

(19, '2004-11-07', '106.238.579-95', '(44) 9-8828-7181', 'M'), 

(20, '2005-08-30', '117.857.129-77', '(44) 9-8819-1616', 'F'), 

(21, '1987-04-13', '060.555.269-05', '(44) 9-8841-1717', 'F'), 

(22, '1977-02-15', '028.581.539-30', '(44) 9-9914-7571', 'M'), 

(23, '2004-11-07', '122.030.639-80', '(44) 9-8828-7181', 'M'), 

(24, '2004-11-11', '263.314.690-28', '(44) 9-9696-8422', 'M'); 

 

-- -------------------------------------------------------- 

 

-- 

-- Estrutura para view `funcionarios_da_empresa_do_usuario` 

-- 

DROP TABLE IF EXISTS `funcionarios_da_empresa_do_usuario`; 

 

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `funcionarios_da_empresa_do_usuario`  AS SELECT `funcionario_empresa_cliente`.`id` AS `id`, `funcionario_empresa_cliente`.`nome_funcionario` AS `nome_funcionario`, `funcionario_empresa_cliente`.`email` AS `email`, `funcionario_empresa_cliente`.`telefone` AS `telefone`, `funcionario_empresa_cliente`.`cpf` AS `cpf`, `funcionario_empresa_cliente`.`genero` AS `genero`, `funcionario_empresa_cliente`.`cargo` AS `cargo`, `funcionario_empresa_cliente`.`departamento` AS `departamento`, `empresa_cliente`.`razao_social` AS `razao_social` FROM ((`funcionario_empresa_cliente` join `empresa_cliente` on(`funcionario_empresa_cliente`.`empresa_id` = `empresa_cliente`.`id`)) join `usuario` on(`empresa_cliente`.`usuario_id` = `usuario`.`id`)) ; 

 

-- 

-- Índices para tabelas despejadas 

-- 

 

-- 

-- Índices de tabela `colaborador` 

-- 

ALTER TABLE `colaborador` 

  ADD PRIMARY KEY (`id`), 

  ADD KEY `usuario_id_colaborador_fk` (`id_usuario`); 

 

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

-- Índices de tabela `usuario` 

-- 

ALTER TABLE `usuario` 

  ADD PRIMARY KEY (`id`); 

 

-- 

-- AUTO_INCREMENT para tabelas despejadas 

-- 

 

-- 

-- AUTO_INCREMENT de tabela `colaborador` 

-- 

ALTER TABLE `colaborador` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 

 

-- 

-- AUTO_INCREMENT de tabela `empresa_cliente` 

-- 

ALTER TABLE `empresa_cliente` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117; 

 

-- 

-- AUTO_INCREMENT de tabela `empresa_cliente_cargo` 

-- 

ALTER TABLE `empresa_cliente_cargo` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129; 

 

-- 

-- AUTO_INCREMENT de tabela `empresa_cliente_departamento` 

-- 

ALTER TABLE `empresa_cliente_departamento` 

  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102; 

 

-- 

-- AUTO_INCREMENT de tabela `empresa_cliente_funcionario` 

-- 

ALTER TABLE `empresa_cliente_funcionario` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78; 

 

-- 

-- AUTO_INCREMENT de tabela `ficha_inscricao` 

-- 

ALTER TABLE `ficha_inscricao` 

  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223; 

 

-- 

-- AUTO_INCREMENT de tabela `permissao` 

-- 

ALTER TABLE `permissao` 

  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5; 

 

-- 

-- AUTO_INCREMENT de tabela `treinamento` 

-- 

ALTER TABLE `treinamento` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24; 

 

-- 

-- AUTO_INCREMENT de tabela `usuario` 

-- 

ALTER TABLE `usuario` 

  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25; 

 

-- 

-- Restrições para tabelas despejadas 

-- 

 

-- 

-- Restrições para tabelas `colaborador` 

-- 

ALTER TABLE `colaborador` 

  ADD CONSTRAINT `usuario_id_colaborador_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`); 

 

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

COMMIT; 

 

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */; 

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */; 

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 

 

 