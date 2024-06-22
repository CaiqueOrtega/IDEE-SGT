<?php
use FontLib\Table\Type\head;

require '../../src/libs/dompdf-master/vendor/autoload.php';
require('../../api/private/connect.php');

$connection = new Database();
session_start();
$id = $_SESSION['login']['id'];

$pdo = $connection->connection();

$sql = "SELECT * FROM `usuario`
        INNER JOIN `login`
        ON usuario.id = login.id 
        WHERE usuario.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($usersData);
$genero = ($usersData[0]['genero'] === 'F') ? 'Feminino' : 'Masculino';



// Capturar o valor do filtro da URL
$turmaModal = isset($_GET['turmaId']) ? $_GET['turmaId'] : null;
$filtro = isset($_GET['aluno']) ? $_GET['aluno'] : null;
echo ($turmaModal);
echo ($filtro);

// Adicionar aqui a lógica para filtrar os treinamentos
$turmasSql = "SELECT turma.*, 
turma.id AS turma_id,
treinamento.*, 
empresa_cliente.*,
login.id AS colaborador_id,
login.nome AS nome_colaborador
FROM `turma`
INNER JOIN `login` ON turma.colaborador_id_fk = login.id
INNER JOIN `empresa_cliente` ON turma.empresa_aluno = empresa_cliente.id
INNER JOIN `treinamento` ON turma.treinamento_id = treinamento.id
WHERE turma.id = :turma_id";

$turmasStmt = $pdo->prepare($turmasSql);
$turmasStmt->bindParam(':turma_id', $turmaModal, PDO::PARAM_INT);
$turmasStmt->execute();

$turmasData = $turmasStmt->fetchAll(PDO::FETCH_ASSOC);



$alunosSql = "SELECT aluno.*, 
        aluno.id AS aluno_id,
        empresa_cliente_funcionario.id AS id_funcionario_fk,
        empresa_cliente_funcionario.* 
        FROM aluno
        INNER JOIN turma ON aluno.turma_aluno_fk = turma.id 
        INNER JOIN empresa_cliente_funcionario ON aluno.id_funcionario_fk = empresa_cliente_funcionario.id
        WHERE aluno.turma_aluno_fk = :turma_id AND aluno.id = :aluno_Id" ;

$alunosStmt = $pdo->prepare($alunosSql);
$alunosStmt->bindParam(':turma_id', $turmaModal, PDO::PARAM_INT);
$alunosStmt->bindParam(':aluno_Id', $filtro, PDO::PARAM_INT);
$alunosStmt->execute();

$alunosData = $alunosStmt->fetchAll(PDO::FETCH_ASSOC);





?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório</title>
    <style>
        table {
            border-collapse: collapse;
            margin-top: 20px;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div style="width: 20%; text-align: right;">
            <img src="" style="width: 120px;">
        </div>
        <div style="width: 80%; text-align: center;">
            <h2>Relatório de Inscrições Pendentes</h2>
        </div>
    </header>
    <div style="text-align: justify;">
        <p>Este relatório tem como objetivo oferecer uma perspectiva abrangente sobre as inscrições pendentes em treinamentos para as empresas registradas pelo cliente responsável. A compilação destas informações visa apresentar detalhes fundamentais sobre as inscrições de treinamento, tanto a nível individual das empresas quanto de maneira geral para todas as inscrições pendentes vinculadas às empresas. Além de destacar aspectos essenciais sobre as inscrições, focando exclusivamente nas informações relacionadas aos funcionários, suas respectivas empresas e os treinamentos associados.</p>
        <p> </p>
    </div>
    <hr>
    <div style="width: 100%; text-align: center; margin-bottom: -24px;  margin-top: -24px;">
        <h3 style="display: inline-block;">Usuário Solicitante</h3>
    </div>
    <hr>
    <?php if (!empty($usersData)): ?>
        <span style="float: right;">E-mail: <?php echo $usersData[0]['email']; ?></span>
        <span style="float: left;">Nome: <?php echo $usersData[0]['nome']; ?></span>
        <br><br>
        <span style="float: right;">CPF: <?php echo $usersData[0]['cpf']; ?></span>
        <span style="float: left;">Gênero: <?php echo $genero; ?></span>
        <br><br>
        <span style="float: right;">Telefone: <?php echo $usersData[0]['telefone']; ?></span>
        <span style="float: left;">Data de Nascimento: <?php echo date('d-m-Y', strtotime($usersData[0]['data_nascimento'])); ?></span>
        <br>
    <?php endif; ?>
    <hr>
    <h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">1.</span> Inscrições Pendentes:</h2>
    <p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">Nesta parte do relatório, concentraremos nossa atenção nas inscrições pendentes em treinamentos, oferecendo uma visão detalhada das solicitações aguardando confirmação. Aqui, você encontrará informações específicas sobre os cursos, datas de inscrição e as empresas envolvidas</p>
    <table style="margin-top: 60px; width: 100%;">
        <thead>
            <tr>
                <th>Treinamento Solicitado</th>
                <th>Empresa Solicitante</th>
                <th>Colaborador</th>
                <th>Carga horaria</th>
                <th>Data de Inicio</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($turmasData as $turma): ?>
                <tr>
                    <td style="max-width: 300px; word-wrap: break-word;"><?php echo $turma['nome_turma']; ?></td>
                    <td style="max-width: 150px; word-wrap: break-word;"><?php echo $turma['nomenclatura']; ?></td>
                    <td style="max-width: 150px; word-wrap: break-word;"><?php echo $turma['nome_colaborador']; ?></td>
                    <td style="max-width: 2px; word-wrap: break-word;"><?php echo $turma['carga_horaria']; ?></td>
                    <td style="max-width: 50px; word-wrap: break-word;"><?php echo date('d-m-Y', strtotime($turma['data_inicio'])); ?></td>
                    <td style="max-width: 50px; word-wrap: break-word;"><?php echo date('d-m-Y', strtotime($turma['data_conclusao'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">2.</span> Inscrições:</h2>
    <p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">.</p>
   <?php $count = count($alunosData);
     foreach ($alunosData as $aluno){ ?>
        <table style="margin-bottom: -20px; width: 100%;">
            
        </table>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Registro</th>
                    <th>Aluno</th>
                    <th>Documento</th>
                    <th>Genero</th>
                    <th>Status</th>
                    <th>Carga Horária</th>
                    <th>Carga Horária</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="max-width: 10px; word-wrap: break-word;"><?php echo $aluno['numero_registro_empresa']; ?></td>
                    <td style="max-width: 100px; word-wrap: break-word;"><?php echo $aluno['nome_funcionario']; ?></td>
                    <td style="max-width: 180px; word-wrap: break-word;"><?php echo $aluno['cpf']; ?></td>
                    <td style="max-width: 2px; word-wrap: break-word;"><?php echo $aluno['genero']; ?></td>
                    <td style="max-width: 2px; word-wrap: break-word;"><?php echo $aluno['status']; ?></td>
                    <td style="max-width: 2px; word-wrap: break-word;"><?php echo $aluno['nota_media']; ?></td>
                    <td style="max-width: 2px; word-wrap: break-word;"><?php echo $aluno['frequencia']; ?></td>
                </tr>
            </tbody>
        </table>
    <?php }?>
    <div class="footer">
        <span>___________________________</span><br>
        <span><?php echo $usersData[0]['nome']; ?></span><br>
        <span><?php echo $usersData[0]['cpf']; ?></span><br>
        <span><?php echo date('d/m/Y'); ?></span>
    </div>
</body>
</html>
