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

$turmaModal = isset($_GET['turmaId']) ? $_GET['turmaId'] : null;
$filtro = isset($_GET['aluno']) ? $_GET['aluno'] : null;

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
        WHERE aluno.turma_aluno_fk = :turma_id AND aluno.id = :aluno_Id";

$alunosStmt = $pdo->prepare($alunosSql);
$alunosStmt->bindParam(':turma_id', $turmaModal, PDO::PARAM_INT);
$alunosStmt->bindParam(':aluno_Id', $filtro, PDO::PARAM_INT);
$alunosStmt->execute();

$alunosData = $alunosStmt->fetchAll(PDO::FETCH_ASSOC);

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setDefaultFont('Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

$imagePath1 = realpath(__DIR__ . '/../../src/img/Certificado De Participação No Curso Azul Claro e Azul Escuro.png');
$imagePath2 = realpath(__DIR__ . '/../../src/img/assinaturaCristiano.png');
$imagePath3 = realpath(__DIR__ . '/../../src/img/logoCertificado.png');

$html = '<html><head>';
$html .= '<style>';
$html .= '@page { margin: 0px; }';
$html .= 'body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-image: url(data:image/png;base64,' . base64_encode(file_get_contents($imagePath1)) . '); background-size: cover; background-position: center; height: 100%; width: 100%; position: relative; }';
$html .= '.content { margin: 40px; position: relative; z-index: 1; }';
$html .= 'table { border-collapse: collapse; width: 100%; margin-top: 20px; }';
$html .= 'th, td { padding: 10px; text-align: left; border: 1px solid black; }';
$html .= 'tr:nth-child(even) { background-color: #f2f2f2; }';
$html .= 'header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }';
$html .= '.centered { text-align: center; }';
$html .= '.footer { position: absolute; bottom: 30px; left: 0; right: 0; text-align: center; }';
$html .= '.header-logo { display: flex; align-items: center; }';
$html .= '.header-logo img { margin-right: 20px; }'; // Add spacing between image and title
$html .= '.header-title { font-size: 24px; font-weight: bold; }';
$html .= '.certificate-body { text-align: justify; margin: 20px 0; padding: 20px; }';
$html .= '.section-title { text-align: center; font-size: 18px; font-weight: bold; margin: 20px 0; }';
$html .= '.aluno-info { margin-bottom: 20px; padding: 10px; }';
$html .= '.aluno-info span { display: block; margin-bottom: 10px; }';
$html .= 'h4, h5 { font-weight: normal; }'; // Remove negrito de h4 e h5
$html .= '</style>';
$html .= '</head><body>';

$html .= '<div class="content">';

$html .= '<div class="header-logo">';
$html .= '<img src="data:image/png;base64,' . base64_encode(file_get_contents($imagePath3)) . '" style="width: 250px; float: left; margin-bottom: 10px;">';

$html .= '<div class="header-title">';
$html .= '<h1>Certificado de Conclusão</h1>';
foreach ($alunosData as $aluno) {
    foreach ($turmasData as $turma) {
        $html .= '<h4 style=";">Por ter atendido os requisitos necessários confere-se o presente certificado a:</h4>';
        $html .= '<h1 style="text-align: center;">' . $aluno['nome_funcionario'] . '</h1>';
        $html .= '<h4 style="text-align: center;">Portador do CPF:' . $aluno['cpf'] . ', concluiu no dia ' . date('d/m/Y', strtotime($turma['data_conclusao'])) . ' o treinamento de:</h4>';
        $html .= '<h5 style="text-align: center;">CURSO DE SEGURANÇA NO TRABALHO EM ' . $turma['nomenclatura'] . ' (' . $turma['nr'] . ')</h5>';
        $html .= '<h5 style="text-align: center;">perfazendo 04 horas aula, '.$turma['horas_teorica'].' teórica e '.$turma['horas_pratica'].' prática, nas dependências da '.$turma['razao_social'].', de acordo com a NR '.$turma['nr'].' - '.$turma['nomenclatura'].'.</h5>';
    }
}
$html .= '</div>';
$html .= '</div>';

// $html .= '<div class="certificate-body" style="font-size: 18px;">';
// $html .= '<p>Este certificado é concedido ao aluno <strong>' . $aluno['nome_funcionario'] . '</strong> em reconhecimento pela conclusão bem-sucedida deste treinamento. Este treinamento foi projetado para fornecer uma compreensão abrangente dos tópicos abordados, promovendo o desenvolvimento de habilidades e conhecimentos fundamentais na área.</p>';
// $html .= '<p>Ao concluir este treinamento, o aluno demonstrou dedicação e competência, atendendo a todos os requisitos e participando ativamente das atividades e avaliações propostas. Esta conquista reflete o compromisso com o aprendizado contínuo e a excelência profissional.</p>';
// $html .= '</div>';

$html .= '<div class="footer">';
$html .= '<img src="data:image/png;base64,' . base64_encode(file_get_contents($imagePath2)) . '" style="width: 210px;">';
$html .= '</div>';

$html .= '</div>';

$html .= '<script type="text/php">';
$html .= 'if (isset($pdf)) {';
$html .= '$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");';
$html .= '$size = 12;';
$html .= '$pageText = "Página " . $PAGE_NUM;';
$html .= '$pdf->text(510, 800, $pageText, $font, $size);';
$html .= '}';
$html .= '</script>';

$html .= '</body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('documento.pdf', array('Attachment' => 0));
?>
