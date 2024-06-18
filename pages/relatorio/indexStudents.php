<?php
include('../class/controller/listClass.php');
require '../../src/libs/dompdf-master/vendor/autoload.php';

// Receber o ID da turma da URL
if (isset($_GET['turma_id'])) {
    $turmaId = $_GET['turma_id'];
} else {
    echo "ID da turma não fornecido.";
    exit;
}

// Conectar ao banco de dados
$pdo = $connection->connection();

// Consultar os dados do usuário solicitante
$sql = "SELECT * FROM `usuario`
        INNER JOIN `login` ON usuario.id = login.id 
        WHERE usuario.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir o gênero do usuário solicitante
$genero = ($usersData[0]['genero'] === 'F') ? 'Feminino' : 'Masculino';



// Consultar os dados dos alunos da turma específica
$sqlAlunos = "SELECT * FROM `aluno`
              WHERE `turma_aluno_fk` = :turma_id";

$stmtAlunos = $pdo->prepare($sqlAlunos);
$stmtAlunos->bindParam(':turma_id', $turmaId, PDO::PARAM_INT);
$stmtAlunos->execute();
$alunosData = $stmtAlunos->fetchAll(PDO::FETCH_ASSOC);

use Dompdf\Dompdf;
use Dompdf\Options;

// Configurações do Dompdf
$options = new Options();
$options->setDefaultFont('Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Gerar o conteúdo HTML do relatório
$html = '<html><head>';
$html .= '<style>';
$html .= 'table { border-collapse: collapse; margin-top: 20px; }';
$html .= 'table, th, td { border: 1px solid black; }';
$html .= 'th, td { padding: 10px; text-align: left; }';
$html .= 'tr:nth-child(even) { background-color: #f2f2f2; }';
$html .= 'header { display: flex; justify-content: space-between; align-items: center; }';
$html .= '</style>';
$html .= '</head><body>';

$imagePath = realpath(__DIR__ . '/../../src/img/logo3.png');
$html .= '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="width: 20%; text-align: right;"><img src="data:image/png;base64,' . base64_encode(file_get_contents($imagePath)) . '" style="width: 120px;"></td>';
$html .= '<td style="width: 80%; text-align: center;"><h2>Relatório de Inscrições Pendentes</h2></td>';
$html .= '</tr>';
$html .= '</table>';

$html .= '<div style="text-align: justify;">';
$html .= '<p>Este relatório tem como objetivo oferecer uma perspectiva abrangente sobre as inscrições pendentes em treinamentos para as empresas registradas pelo cliente responsável. A compilação destas informações visa apresentar detalhes fundamentais sobre as inscrições de treinamento, tanto a nível individual das empresas quanto de maneira geral para todas as inscrições pendentes vinculadas às empresas. Além de destacar aspectos essenciais sobre as inscrições, focando exclusivamente nas informações relacionadas aos funcionários, suas respectivas empresas e os treinamentos associados.</p>';
$html .= '<p> </p>';
$html .= '</div>';

$html .= '<br>';
$html .= '<hr>';
$html .= '<div style="width: 100%; text-align: center; margin-bottom: -24px;  margin-top: -24px;">';
$html .= '<h3 style="display: inline-block;">Usuário Solicitante</h3>';
$html .= '</div>';
$html .= '<hr>';

if (!empty($usersData)) {
    $html .= '<span style="float: right;">E-mail: ' . $usersData[0]['email'] . '</span>';
    $html .= '<span style="float: left;">Nome: ' . $usersData[0]['nome'] . '</span>';
    $html .= '<br><br>';
    $html .= '<span style="float: right;">CPF: ' . $usersData[0]['cpf'] . '</span>';
    $html .= '<span style="float: left;">Gênero: ' . $genero . '</span>';
    $html .= '<br><br>';
    $html .= '<span style="float: right;">Telefone: ' . $usersData[0]['telefone'] . '</span>';
    $html .= '<span style="float: left;">Data de Nascimento: ' . date('d-m-Y', strtotime($usersData[0]['data_nascimento'])) . '</span>';
    $html .= '<br>';
}

$html .= '<hr>';

$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">1.</span>Inscrições Pendentes:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">Nesta parte do relatório, concentraremos nossa atenção nas inscrições pendentes em treinamentos, oferecendo uma visão detalhada das solicitações aguardando confirmação. Aqui, você encontrará informações específicas sobre os cursos, datas de inscrição e as empresas envolvidas</p>';

$html .= '<table style="margin-top: 60px; width: 100%;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Treinamento Solicitado</th>';
$html .= '<th>Empresa Solicitante</th>';
$html .= '<th>Data Solicitação</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Iterar sobre os dados das turmas e adicionar ao relatório
foreach ($turmasData as $turma) {
    
    $html .= '<tr>';
    $html .= '<td style="max-width: 300px; word-wrap: break-word;">' . $turma['nomenclatura'] . '</td>';
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' . $turma['razao_social'] . '</td>';
    $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . date('d-m-Y', strtotime($turma['data_inicio'])) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">2.</span>Inscrições:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;"></p>';

// Iterar sobre os dados das turmas e adicionar ao relatório
$count = count($turmasData);
foreach ($turmasData as $key => $turma) {
    $turmaId = $turma['turma_id'];
    $alunosData = getAlunosData($id, $connection, $whereAluno, $turmaId);

    $html .= '<table style="margin-bottom: -20px; width: 100%;">';
    $html .= '<tr>';
    $html .= '<td style="width: 80%;"><h3>Treinamento Solicitado pela ' . $turma['razao_social'] . '</h3></td>';
    $html .= '<td style="width: 20%; text-align: center;"><h3>Data<br>' . date('d-m-Y', strtotime($turma['data_inicio'])) . '</h3></td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= '<table style="width: 100%;">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>NR</th>';
    $html .= '<th>Nomenclatura</th>';
    $html .= '<th>Objetivo</th>';
    $html .= '<th>Carga Horaria</th>';
    $html .= '<th>Horas Praticas</th>';
    $html .= '<th>Horas Teoricas</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $turma['nr'] . '</td>';
    $html .= '<td style="max-width: 100px; word-wrap: break-word;">' . $turma['nomenclatura'] . '</td>';
    $html .= '<td style="max-width: 180px; word-wrap: break-word;">' . $turma['objetivo'] . '</td>';
    $html .= '<td style="max-width: 2px; word-wrap: break-word;">' . $turma['carga_horaria'] . '</td>';
    $html .= '<td style="max-width: 2px; word-wrap: break-word;">' . $turma['horas_pratica'] . '</td>';
    $html .= '<td style="max-width: 2px; word-wrap: break-word;">' . $turma['horas_teorica'] . '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';

    $html .= '<div style="width: 100%; border: 1px solid black; margin-bottom: -20px; margin-top: 0px; text-align: left;">';
    $html .= '<h3 style="margin-left: 20px;">Funcionário Inscritos</h3>';
    $html .= '</div>';
    $html .= '<table style="width: 100%;">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Registro</th>';
    $html .= '<th>Nome</th>';
    $html .= '<th>Documento</th>';
    $html .= '<th>Gênero</th>';
    $html .= '<th>Status Aluno</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Iterar sobre os dados dos alunos e adicionar ao relatório
    foreach ($alunosData as $aluno) {
        $html .= '<tr>';
        $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . $aluno['numero_registro_empresa'] . '</td>';
        $html .= '<td style="max-width: 70px; word-wrap: break-word;">' . $aluno['nome_funcionario'] . '</td>';
        $html .= '<td style="max-width: 70px; word-wrap: break-word;">' . $aluno['cpf'] . '</td>';
        $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . $aluno['genero'] . '</td>';
        $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $aluno['status'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';

    if ($key < $count - 1) {
        $html .= '<div style="page-break-before: always;"></div>';
    }

}
$html .= '<style>';
$html .= '.footer { position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; }';
$html .= '</style>';
$html .= '<div class="footer">';
$html .= '<span>___________________________</span><br>';
$html .= '<span>' . $usersData[0]['nome'] . '</span><br>';
$html .= '<span>' . $usersData[0]['cpf'] . '</span><br>';
$html .= '<span>' . date('d/m/Y') . '</span>';
$html .= '</div>';

$html .= '<script type="text/php">';
$html .= 'if (isset($pdf)) {';
$html .= '$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");';
$html .= '$size = 12;';
$html .= '$pageText = "Página " . $PAGE_NUM;';
$html .= '$y = 800;';
$html .= '$x = 510;';
$html .= '$pdf->text($x, $y, $pageText, $font, $size);';
$html .= '}';
$html .= '</script>';

$html .= '</body></html>';

// Carregar e renderizar o HTML no Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('documento.pdf', array('Attachment' => 0));
?>
