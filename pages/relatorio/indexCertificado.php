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

// $genero = ($usersData['genero'] === 'F') ? 'Feminino' : 'Masculino';



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
$html .= '<td style="width: 20%; text-align: center;"><img src="data:image/png;base64,' . base64_encode(file_get_contents($imagePath)) . '" style="width: 120px;"></td>';
$html .= '<td style="width: 80%; text-align: center;"><h2>Certificado de Conclusão</h2></td>';
$html .= '</tr>';
$html .= '</table>';

$html .= '<div style="text-align: justify;">';
$html .= '<p>Este certificado é concedido ao aluno em reconhecimento pela conclusão bem-sucedida deste treinamento Este treinamento foi projetado para fornecer uma compreensão abrangente dos tópicos abordados, promovendo o desenvolvimento de habilidades e conhecimentos fundamentais na área.</p>';
$html .= '<p>Ao concluir este treinamento, O aluno demonstrou dedicação e competência, atendendo a todos os requisitos e participando ativamente das atividades e avaliações propostas. Esta conquista reflete o compromisso com o aprendizado contínuo e a excelência profissional.</p>';
$html .= '</div>';

$html .= '<br>';
$html .= '<hr>';
$html .= '<div style="width: 100%; text-align: center; margin-bottom: -9px;  margin-top: -24px;">';
$html .= '<h3 style="display: inline-block;">Dados do Aluno</h3>';
$html .= '</div>';
$html .= '<hr>';

foreach ($alunosData as $aluno) {

    $html .= '<span style="float: right;">Telefone: ' . $aluno['telefone'] . '</span>';
    $html .= '<span style="float: left;">Nome: ' . $aluno['nome_funcionario'] . '</span>';
    $html .= '<br><br>';
    $html .= '<span style="float: left;">E-mail: ' . $aluno['email'] . '</span>';
    $html .= '<span style="float: right;">CPF: ' . $aluno['cpf'] . '</span>';

    $html .= '<br>';
}

$html .= '<hr>';

$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;"></span>Dados da turma:</h2>';
// $html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">Nesta parte do relatório, concentraremos nossa atenção nas inscrições pendentes em treinamentos, oferecendo uma visão detalhada das solicitações aguardando confirmação. Aqui, você encontrará informações específicas sobre os cursos, datas de inscrição e as empresas envolvidas</p>';

$html .= '<table style="margin-top: 10px; width: 100%;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Turma</th>';
$html .= '<th>Treinamento</th>';
$html .= '<th>Colaborador</th>';
$html .= '<th>Carga Horaria</th>';
$html .= '<th>Data de Inicio</th>';
$html .= '<th>Data de Conclusão</th>';
$html .= '<th>Media</th>';
$html .= '<th>Frequencia</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Iterar sobre os dados das turmas e adicionar ao relatório
foreach ($turmasData as $turma) {
  
    $html .= '<tr>';
    $html .= '<td style="max-width: 300px; word-wrap: break-word;">' . $turma['nome_turma'] . '</td>';
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' . $turma['nomenclatura'] . '</td>';
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' . $turma['nome_colaborador'] . '</td>';
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' .  $turma['carga_horaria'] ." H ". '</td>';
    $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . date('d/m/Y', strtotime($turma['data_inicio'])) . '</td>';
    $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . date('d/m/Y', strtotime($turma['data_conclusao'])) . '</td>';
  } 
  foreach ($alunosData as $aluno) {
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' . $aluno['nota_media'] . '</td>';
    $html .= '<td style="max-width: 150px; word-wrap: break-word;">' . ($aluno['frequencia'] ) . '%</td>';

    $html .= '</tr>';
} 

$html .= '</tbody>';
$html .= '</table>';

// $html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">1.</span>Inscrições:</h2>';
// $html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;"></p>';

// Iterar sobre os dados das turmas e adicionar ao relatório
// $count = count($turmasData);
// foreach ($turmasData as $key => $turma) {
  

    // $html .= '<table style="margin-bottom: -20px; width: 100%;">';
    // $html .= '<tr>';
    // $html .= '<td style="width: 80%;"><h3>Treinamento Solicitado pela ' . $turma['razao_social'] . '</h3></td>';
    // $html .= '<td style="width: 20%; text-align: center;"><h3>Data<br>' . date('d-m-Y', strtotime($turma['data_inicio'])) . '</h3></td>';
    // $html .= '</tr>';
    // $html .= '</table>';

    // $html .= '<table style="width: 100%;">';
    // $html .= '<thead>';
    // $html .= '<tr>';
    // $html .= '<th>NR</th>';
    // $html .= '<th>Nomenclatura</th>';
    // $html .= '<th>Objetivo</th>';
    // $html .= '<th>Carga Horaria</th>';
    // $html .= '<th>Horas Praticas</th>';
    // $html .= '<th>Horas Teoricas</th>';
    // $html .= '</tr>';
    // $html .= '</thead>';
    // $html .= '<tbody>';
    // $html .= '<tr>';
    // $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $turma['nome_turma'] . '</td>';
    // $html .= '<td style="max-width: 100px; word-wrap: break-word;">' . $turma['nomenclatura'] . '</td>';
    // $html .= '<td style="max-width: 180px; word-wrap: break-word;">' . $turma['nome_colaborador'] . '</td>';
    // $html .= '<td style="max-width: 2px; word-wrap: break-word;">' . $turma['carga_horaria'] . '</td>';
    // $html .= '<td style="max-width: 2px; word-wrap: break-word;">' . date('d-m-Y', strtotime($turma['data_inicio'])) . '</td>';
    // $html .= '</tr>';
    // $html .= '</tbody>';
    // $html .= '</table>';

    // $html .= '<div style="width: 100%; border: 1px solid black; margin-bottom: -20px; margin-top: 0px; text-align: left;">';
    // $html .= '<h3 style="margin-left: 20px;">Funcionário Inscritos</h3>';
    // $html .= '</div>';
    // $html .= '<table style="width: 100%;">';
    // $html .= '<thead>';
    // $html .= '<tr>';
    // $html .= '<th>Registro</th>';
    // $html .= '<th>Nome</th>';
    // $html .= '<th style="width: 120px;">Documento</th>';
    // $html .= '<th>Gênero</th>';
    // $html .= '<th>Status Aluno</th>';
    // $html .= '<th>Media</th>';
    // $html .= '<th>Frequencia</th>';
    // $html .= '</tr>';
    // $html .= '</thead>';
    // $html .= '<tbody>';

    // // Iterar sobre os dados dos alunos e adicionar ao relatório
    // foreach ($alunosData as $aluno) {
    //     $html .= '<tr>';
    //     $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . $aluno['numero_registro_empresa'] . '</td>';
    //     $html .= '<td style="max-width: 70px; word-wrap: break-word;">' . $aluno['nome_funcionario'] . '</td>';
    //     $html .= '<td style="max-width: 90px; word-wrap: break-word;">' . $aluno['cpf'] . '</td>';
    //     $html .= '<td style="max-width: 50px; word-wrap: break-word;">' . $aluno['genero'] . '</td>';
    //     $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $aluno['status'] . '</td>';
    //     $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $aluno['nota_media'] . '</td>';
    //     $html .= '<td style="max-width: 10px; word-wrap: break-word;">' . $aluno['frequencia'] . '</td>';
    //     $html .= '</tr>';
    // }
    // $html .= '</tbody>';
    // $html .= '</table>';

    // if ($key < $count - 1) {
    //     $html .= '<div style="page-break-before: always;"></div>';
    // }

// }
$html .= '<style>';
$html .= '  .footer {';
$html .= '    position: absolute;';
$html .= '    bottom: -30px;';  // Ajustei a posição do footer para mais perto da borda inferior
$html .= '    left: 0;';
$html .= '    right: 0;';
$html .= '    text-align: center;';
$html .= '  }';
$html .= '</style>';

$html .= '<div class="footer">';
$html .= '    <span>___________________________</span><br>';
$html .= '    <span>' . $usersData[0]['nome'] . '</span><br>';
$html .= '    <span> Cianorte - PR </span>';
$html .= '    <span> ' . date('d/m/Y') .  '</span>';
$html .= '</div>';

$html .= '<script type="text/php">';
$html .= '  if ( isset($pdf) ) {';
$html .= '    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");';
$html .= '    $size = 12;';
$html .= '    $pageText = "Página " . $PAGE_NUM;';
$html .= '    $pdf->text(510, 800, $pageText, $font, $size);';
$html .= '  }';
$html .= '</script>';

$html .= '</body></html>';



$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('documento.pdf', array('Attachment' => 0));


