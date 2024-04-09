<?php
include('../employee/controller/list.php');
require '../../src/libs/dompdf-master/vendor/autoload.php';


$pdo = $connection->connection();

$sql = "SELECT * FROM `usuario`
        INNER JOIN `login`
        ON usuario.id = login.id 
        WHERE usuario.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();

$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($usersData[0]['genero'] === 'F') {
    $genero = 'Feminino';
} else {
    $genero = 'Masculino';
}

function getCargosEmployess($pdo, $empresaId)
{
    $sql = "SELECT empresa_cliente_funcionario.*, 
    empresa_cliente.razao_social, 
    empresa_cliente.id AS empresa_id, 
    empresa_cliente_cargo.nome AS cargo_nome, 
    empresa_cliente_departamento.nome AS departamento_nome 
FROM `empresa_cliente_funcionario` 
INNER JOIN `empresa_cliente` ON empresa_cliente_funcionario.empresa_id = empresa_cliente.id 
LEFT JOIN `empresa_cliente_cargo` ON empresa_cliente_funcionario.cargo_id = empresa_cliente_cargo.id 
LEFT JOIN `empresa_cliente_departamento` ON empresa_cliente_funcionario.departamento_id = empresa_cliente_departamento.id 
INNER JOIN usuario ON empresa_cliente.usuario_id = usuario.id 
WHERE empresa_cliente.id  = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Canvas;

// Criar uma instância do Dompdf
$options = new Options();
$options->setDefaultFont('Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

$html = '<html><head>';


$html .= '<style>';
$html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
$html .= 'table, th, td { border: 1px solid black; }';
$html .= 'th, td { padding: 10px; text-align: left; }';
$html .= 'tr:nth-child(even) { background-color: #f2f2f2; }';
$html .= 'header { display: flex; justify-content: space-between; align-items: center; }';
$html .= '</style>';


$html .= '</head><body>';

$imagePath = realpath(__DIR__ . '/../../src/img/logo3.png');

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 20%; text-align: right;"><img src="data:image/png;base64,' . base64_encode(file_get_contents($imagePath)) . '" style="width: 120px;"></td>';
$html .= '<td style="width: 80%; text-align: center;"><h2>Relatório de empresas cadastradas</h2></td>';
$html .= '</tr>';
$html .= '</table>';

$html .= '<div style="text-align: justify;">';
$html .= '<p>Este relatório tem como propósito fornecer uma visão abrangente sobre os funcionários das empresas cadastradas pelo cliente 
responsável. A compilação destas informações visa apresentar detalhes fundamentais dos colaboradores, 
tanto a nível individual das empresas quanto de maneira geral para todos os funcionários vinculados as empresas. 
Além de destacar aspectos essenciais sobre os funcionários, focando exclusivamente nas informações relacionadas aos funcionários e suas respectivas empresas </p>';
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

    $html .= '<br>';
    $html .= '<br>';

    $html .= '<span style="float: right;">CPF: ' . $usersData[0]['cpf'] . '</span>';
    $html .= '<span style="float: left;">Genêro: ' . $genero . '</span>';

    $html .= '<br>';
    $html .= '<br>';
    $html .= '<span style="float: right;">Telefone: ' . $usersData[0]['telefone'] . '</span>';
    $html .= '<span style="float: left;">Data de Nascimento: ' . date('d-m-Y', strtotime($usersData[0]['data_nascimento'])) . '</span>';
    $html .= '<br>';
}

$html .= '<hr>';

$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">1.</span>Funcionários:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">A seguir, apresentamos informações detalhadas sobre os funcionários cadastrados, oferecendo uma visão abrangente e minuciosa dos registros realizados.</p>';
$html .= '<table style="margin-top: 60px">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Nome</th>';
$html .= '<th>E-mail</th>';
$html .= '<th>Telefone</th>';
$html .= '<th>Genero</th>';
$html .= '<th>CPF</th>';
$html .= '<th>Empresa</th>';
$html .= '<th>Número Registro</th>';

$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

foreach ($funcionariosData as $funcionario) {
    $html .= '<tr>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $funcionario['nome_funcionario'] . '</td>';
    $html .= '<td style="max-width: 70; word-wrap: break-word;">' . $funcionario['email'] . '</td>';
    $html .= '<td style="max-width: 60; word-wrap: break-word;">' . $funcionario['telefone'] . '</td>';
    $html .= '<td style="max-width: 1; word-wrap: break-word;">' . $funcionario['genero'] . '</td>';
    $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $funcionario['cpf'] . '</td>';
    $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $funcionario['razao_social'] . '</td>';
    $html .= '<td style="max-width: 1; word-wrap: break-word;">' . $funcionario['numero_registro_empresa'] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';








$html .= '<h2 style="display: inline-block; margin-top: 70px;"><span style="font-weight: bold; font-size: 23px;">2.</span>Funcionários:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">Este relatório apresenta uma listagem abrangente de todos os funcionários, destacando informações cruciais sobre suas respectivas empresas, cargos e departamentos. A seguir, você encontrará detalhes valiosos que oferecem uma visão completa da composição da força de trabalho.</p>';
foreach ($empresasData as $empresa) {

    $funcionariox = getCargosEmployess($pdo, $empresa['id']);
    $html .= '<div style="width: 100%; border: 1px solid black; margin-bottom: -20px; margin-top: 50px; text-align: left;">';
    $html .= '<h3 style="margin-left: 20px;">' . $empresa['nome_fantasia'] . '</h3>';
    $html .= '</div>';
    
    if (!empty($funcionariox)) {
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>E-mail</th>';
        $html .= '<th>Telefone</th>';
        $html .= '<th>Genero</th>';
        $html .= '<th>CPF</th>';
        $html .= '<th>Número Registro</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';



        foreach ($funcionariox as $funcionarioxx) {
            $html .= '<tr>';
            $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $funcionarioxx['nome_funcionario'] . '</td>';
            $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $funcionarioxx['email'] . '</td>';
            $html .= '<td style="max-width: 98; word-wrap: break-word;">' . $funcionarioxx['telefone'] . '</td>';
            $html .= '<td style="max-width: 1; word-wrap: break-word;">' . $funcionarioxx['genero'] . '</td>';
            $html .= '<td style="max-width: 92; word-wrap: break-word;">' . $funcionarioxx['cpf'] . '</td>';
            $html .= '<td style="max-width: 1; word-wrap: break-word;">' . $funcionarioxx['numero_registro_empresa'] . '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</tbody>';
    $html .= '</table>';
}




$html .= '<style>';
$html .= '  .footer {';
$html .= '    position: absolute;';
$html .= '    bottom: 20px;';  
$html .= '    left: 0;';
$html .= '    right: 0;';
$html .= '    text-align: center;';
$html .= '  }';
$html .= '</style>';

$html .= '<div class="footer">';
$html .= '  <span>___________________________</span><br>';
$html .= '  <span>'. $usersData[0]['nome']. '</span><br>';
$html .= '  <span>'.$usersData[0]['cpf'].'</span><br>';
$html .= '  <span> '. date('d/m/Y') . '</span>';
$html .= '</div>';

$html .= '<script type="text/php" width="100%" style="position: fixed; bottom: 0;">';
$html .= '    if ( isset($pdf) ) {';
$html .= '        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");';
$html .= '        $size = 12;';
$html .= '        $pageText = "Página " . $PAGE_NUM;';
$html .= '        $y = 800;'; 
$html .= '        $x = 510;'; 
$html .= '        $pdf->text($x, $y, $pageText, $font, $size);';
$html .= '    }';
$html .= '</script>';

$dompdf->loadHtml($html);



$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream('documento.pdf', array('Attachment' => 0));















