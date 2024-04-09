<?php
include('../company/controller/list.php');
require '../../src/libs/dompdf-master/vendor/autoload.php';


$pdo = $connection->connection();

$sql = "SELECT * FROM `usuario`
        INNER JOIN `login`
        ON usuario.id = login.id 
        WHERE usuario.id = :id";



$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($usersData[0]['genero'] === 'F') {
    $genero = 'Feminino';
} else {
    $genero = 'Masculino';
}

function getCargosDepartamentos($pdo, $empresaId)
{
    $result = [];

 
    $sqlCargo = "SELECT 
                    cargo.nome AS nome_cargo 
                FROM 
                    empresa_cliente 
                    INNER JOIN empresa_cliente_cargo AS cargo ON empresa_cliente.id = cargo.empresa_id
                WHERE 
                    empresa_cliente.id = :id";

    $stmtCargo = $pdo->prepare($sqlCargo);
    $stmtCargo->bindParam(':id', $empresaId, PDO::PARAM_INT);
    $stmtCargo->execute();
    $result['cargos'] = $stmtCargo->fetchAll(PDO::FETCH_COLUMN);

    
    $sqlDepartamento = "SELECT 
                            departamento.nome AS nome_departamento 
                        FROM 
                            empresa_cliente 
                            INNER JOIN empresa_cliente_departamento AS departamento ON empresa_cliente.id = departamento.empresa_id
                        WHERE 
                            empresa_cliente.id = :id";

    $stmtDepartamento = $pdo->prepare($sqlDepartamento);
    $stmtDepartamento->bindParam(':id', $empresaId, PDO::PARAM_INT);
    $stmtDepartamento->execute();
    $result['departamentos'] = $stmtDepartamento->fetchAll(PDO::FETCH_COLUMN);

    return $result;
}



use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Canvas;


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
$html .= '<p>Este relatório tem por objetivo apresentar de forma consolidada as informações relativas às empresas cadastradas
 pelo cliente responsável. A compilação destes dados visa proporcionar uma visão abrangente sobre as entidades 
 comerciais vinculadas ao cliente, destacando não apenas detalhes fundamentais das empresas, mas também informações essenciais sobre o 
 próprio cliente. </p>';

$html .= '</div>';
$html .= '<br>';
$html .= '<hr>';
$html .= '<div style="width: 100%; text-align: center; margin-bottom: -24px;  margin-top: -24px;">';
$html .= '<h3 style="display: inline-block;">Usuário Solicitante</h3>';
$html .= '</div>';
$html .= '<hr>';

$html .= '<div style="text-align: center;">';


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

$html .= '</div>';
$html .= '<hr>';
$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">1.</span>Empresas:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">Nesta seção, fornecemos uma análise detalhada dos dados das empresas cadastradas, oferecendo uma perspectiva abrangente e minuciosa dos registros disponíveis.</p>';


$html .= '<table style="margin-top: 60px">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th>Razão Social</th>';
$html .= '<th>Nome Fantasia</th>';
$html .= '<th>Telefone</th>';
$html .= '<th>CNPJ</th>';
$html .= '<th>Email</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';



foreach ($empresasData as $empresa) {

    $html .= '<tr>';
    $html .= '<td style="max-width: 70; word-wrap: break-word;">' . $empresa['razao_social'] . '</td>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $empresa['nome_fantasia'] . '</td>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $empresa['telefone'] . '</td>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $empresa['cnpj'] . '</td>';
    $html .= '<td style="max-width: 70; word-wrap: break-word;">' . $empresa['email'] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';




$html .= '<h2 style="display: inline-block; margin-top: 70px;"><span style="font-weight: bold; font-size: 23px;">1.</span>Cargos e Departamentos:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify;">Este relatório apresenta uma listagem abrangente de todas as empresas, destacando informações cruciais sobre suas respectivas categorias, setores e departamentos. A seguir, você encontrará detalhes valiosos que oferecem uma visão completa da composição empresarial.</p>';
foreach ($empresasData as $empresa) {
    $cargosDepartamentos = getCargosDepartamentos($pdo, $empresa['id']);

    if (!empty($cargosDepartamentos)) {
        $html .= '<div style="width: 100%; border: 1px solid black; margin-bottom: -20px; margin-top: 50px;">';
        $html .= '<h3 style="margin-left: 20px;">' . $empresa['razao_social'] . '</h3>';
        $html .= '</div>';

        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome do Cargo</th>';
        $html .= '<th>Nome do Departamento</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $maxRowCount = max(count($cargosDepartamentos['cargos']), count($cargosDepartamentos['departamentos']));

        for ($i = 0; $i < $maxRowCount; $i++) {
            $cargo = $cargosDepartamentos['cargos'][$i] ?? '';
            $departamento = $cargosDepartamentos['departamentos'][$i] ?? '';
        
            $html .= '<tr>';
            $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $cargo . '</td>';
            $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $departamento . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        unset($cargosDepartamentos);
    } else {
        $html .= '<p>Nenhum cargo ou departamento disponível para ' . $empresa['razao_social'] . '</p>';
    }
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
$html .= '    <div class="footer">';
$html .= '        <span>___________________________</span><br>';
$html .= '        <span>' . $usersData[0]['nome'] . '</span><br>';
$html .= '        <span>' . $usersData[0]['cpf'] . '</span><br>';
$html .= '        <span> ' . date('d/m/Y') .  '</span>';
$html .= '    </div>';



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


$html .= '</body>';
$html .= '</html>';



$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');



$dompdf->render();

$dompdf->stream('documento.pdf', array('Attachment' => 0));
