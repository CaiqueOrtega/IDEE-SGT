
<?php
include('../inscription/controller/list.php');
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



function getFuncionariosData($connection, $funcionariosIdsJson)
{
    if (empty($funcionariosIdsJson)) {
        return [];
    }
    $funcionariosIdsArray = json_decode($funcionariosIdsJson, true);

    if (!$funcionariosIdsArray || !is_array($funcionariosIdsArray)) {
        return [];
    }

    // Obtenha os IDs como uma string separada por vírgulas
    $funcionariosIdsString = implode(',', array_map(function ($item) {
        return $item['id'];
    }, $funcionariosIdsArray));


    $sql = "SELECT 
    empresa_cliente_funcionario.*,
    empresa_cliente_cargo.nome AS nome_cargo,
    empresa_cliente_departamento.nome AS nome_departamento
    FROM `empresa_cliente_funcionario`
    INNER JOIN `empresa_cliente_cargo` 
    ON empresa_cliente_funcionario.cargo_id = empresa_cliente_cargo.id
    INNER JOIN `empresa_cliente_departamento`
    ON empresa_cliente_funcionario.departamento_id = empresa_cliente_departamento.id
     WHERE empresa_cliente_funcionario.id IN ($funcionariosIdsString)";

    $stmt = $connection->connection()->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

foreach ($inscricoesData as $inscricao) {
    $html .= '<tr>';
    $html .= '<td style="max-width: 300; word-wrap: break-word;">' . $inscricao['nomenclatura'] . '</td>';
    $html .= '<td style="max-width: 150; word-wrap: break-word;">' . $inscricao['razao_social'] . '</td>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . date('d-m-Y', strtotime($inscricao['data_realizacao'])) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';





$html .= '<h2 style="display: inline-block;"><span style="font-weight: bold; font-size: 23px;">2.</span>Inscrições:</h2>';
$html .= '<p style="font-size: 16px; text-align: justify; margin-bottom: 10px;">.</p>';


$count = count($inscricoesData);
foreach ($inscricoesData as $key => $inscricao) {
    $html .= '<table style="margin-bottom: -20px; width: 100%;">';
    $html .= '<tr>';
    $html .= '<td style="width: 80%;"><h3 >Treinamento Solicitado pela ' . $inscricao['razao_social'] . '</h3></td>';
    $html .= '<td style="width: 20% text-align: center;"><h3 >  Data <br>' .  date('d-m-Y', strtotime($inscricao['data_realizacao'])) . '</h3></td>';
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
    $html .= '<td  style="max-width: 10; word-wrap: break-word;">' . $inscricao['nr'] . '</td>';
    $html .= '<td style="max-width: 100; word-wrap: break-word;">' . $inscricao['nomenclatura'] . '</td>';
    $html .= '<td  style="max-width: 180; word-wrap: break-word;">' . $inscricao['objetivo'] . '</td>';
    $html .= '<td  style="max-width: 2; word-wrap: break-word;">' . $inscricao['carga_horaria'] . '</td>';
    $html .= '<td  style="max-width: 2; word-wrap: break-word;">' . $inscricao['horas_pratica'] . '</td>';
    $html .= '<td  style="max-width: 2; word-wrap: break-word;">' . $inscricao['horas_teorica'] . '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';




    $funcionariosData = getFuncionariosData($connection, $inscricao['funcionarios']);
    $html .= '<div style="width: 100%; border: 1px solid black; margin-bottom: -20px; margin-top: 0px; text-align: left;">';
    $html .= '<h3 style="margin-left: 20px;">Funcionário Inscritos</h3>';
    $html .= '</div>';
    $html .= '<table style="width: 100%;>';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Nome</th>';
    $html .= '<th>E-mail</th>';
    $html .= '<th>CPF</th>';
    $html .= '<th>Telefone</th>';
    $html .= '<th>Cargo</th>';
    $html .= '<th">Departamento</th>';
    $html .= '<th>Número Registro</th>';
    $html .= '</tr>';
    $html .= '</thead>';

    $html .= '<tbody>';
    foreach ($funcionariosData as $funcionario) {
    $html .= '<tr>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $funcionario['nome_funcionario'] . '</td>';
    $html .= '<td style="max-width: 70; word-wrap: break-word;">' . $funcionario['email'] . '</td>';
    $html .= '<td style="max-width: 70; word-wrap: break-word;">' . $funcionario['cpf'] . '</td>';
    $html .= '<td style="max-width: 50; word-wrap: break-word;">' . $funcionario['telefone'] . '</td>';
    $html .= '<td style="max-width: 10; word-wrap: break-word;">' . $funcionario['nome_cargo'] . '</td>';
    $html .= '<td style="max-width: 5; word-wrap: break-word;">' . $funcionario['nome_departamento'] . '</td>';
    $html .= '<td style="max-width: 5; word-wrap: break-word;">' . $funcionario['numero_registro_empresa'] . '</td>';
    $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';


    if ($key < $count - 1) {
        $html .= '<div style="page-break-before: always;"></div>';
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

$html .= '<div class="footer">';
$html .= '  <span>___________________________</span><br>';
$html .= '  <span>' . $usersData[0]['nome'] . '</span><br>';
$html .= '  <span>' . $usersData[0]['cpf'] . '</span><br>';
$html .= '  <span> ' . date('d/m/Y') . '</span>';
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
