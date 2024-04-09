<?php

function isValid($campos = [])
{
    foreach ($campos as $campo) {
        $campoLimpo = str_replace('_', ' ', $campo);
        if (empty($_POST[$campo])) {
            return json_encode([
                'msg' => ucfirst($campoLimpo) . ' não pode ser vazio',
                'status' => 401
            ]);
        }
    }

    return false;
}




function isEmailFormatValid($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function cleanAndValidate($fieldName, $value)
{
    // Inclui caracteres acentuados na expressão regular
    $filteredData = preg_replace('/[^a-zA-ZÇçáéíóúâêîôûàèìòùäëïöüÁÉÍÓÚÂÊÎÔÛÀÈÌÒÙÄËÏÖÜ ]/u', '', $value);

    if ($filteredData === '') {
        echo json_encode(['msg' => "$fieldName não pode ter caracteres ou números", 'status' => 400]);
        exit;
    }

    return $filteredData;
}


function cleanNumbersAndValidate($fieldName, $value)
{
    $filteredData = preg_replace('/[^0-9]/', '', $value);

    if ($filteredData === '') {
        echo json_encode(['msg' => "Campo '$fieldName' não pode ficar vazio.", 'status' => 400]);
        exit;
    }

    return $filteredData;
}


function cleanAndValidatePhoneNumber($fieldName, $value, $format = true)
{
    // Limpar e validar o número de telefone
    $phoneNumber = cleanNumbersAndValidate($fieldName, $value);

    // Verificar se a quantidade de dígitos é válida
    if (!preg_match('/^\d{10,11}$/', $phoneNumber)) {
        echo json_encode(['msg' => "Número de $fieldName inválido", 'status' => 400]);
        exit;
    }

    // Formatar o número para o padrão '(00) 0000-0000' ou '(00) 0-0000-0000' se necessário
    if ($format) {
        if (strlen($phoneNumber) === 11) {
            $formattedValue = sprintf('(%s) %s-%s-%s',
                substr($phoneNumber, 0, 2),
                substr($phoneNumber, 2, 1),
                substr($phoneNumber, 3, 4),
                substr($phoneNumber, 7)
            );
        } elseif (strlen($phoneNumber) === 10) {
            $formattedValue = sprintf('(%s) %s-%s',
                substr($phoneNumber, 0, 2),
                substr($phoneNumber, 2, 4),
                substr($phoneNumber, 6)
            );
        } else {
            // Se o número de dígitos não for 10 ou 11, algo está errado
            echo json_encode(['msg' => "Número de $fieldName inválido", 'status' => 400]);
            exit;
        }

        return $formattedValue;
    }

    return $phoneNumber;
}





function cleanAndValidateCharsNumbers($fieldName, $nome)
{
    $nome = trim($nome);

    // Inclui caracteres acentuados na expressão regular
    $filteredData = preg_replace('/[^a-zA-Z0-9Ççáéíóúâêîôûàèìòùäëïöü\s]/u', '', $nome);

    if ($filteredData === '') {
        echo json_encode(['msg' => "$fieldName não pode ter caracteres", 'status' => 400]);
        exit;
    }

    $filteredData = ucfirst(strtolower($filteredData));

    return $filteredData;
}



function cleanAndValidateCnpj($value)
{
    // Remover caracteres não numéricos
    $cnpj = preg_replace('/[^0-9]/', '', $value);


    // Verificar se o CNPJ é válido
    if (!isCnpjValid($cnpj)) {
        echo json_encode(['msg' => "CNPJ inválido", 'status' => 400]);
        exit;
    }

    // Formatar o CNPJ para o padrão '00.000.000/0000-00'
    $cnpjFormatado = sprintf(
        '%s.%s.%s/%s-%s',
        substr($cnpj, 0, 2),
        substr($cnpj, 2, 3),
        substr($cnpj, 5, 3),
        substr($cnpj, 8, 4),
        substr($cnpj, 12, 2)
    );

    return $cnpjFormatado;
}


function cleanAndValidateCpf($value)
{
    // Remover caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $value);

    // Verificar se o CPF é válido
    if (!isCpfValid($cpf)) {
        echo json_encode(['msg' => "CPF inválido", 'status' => 400]);
        exit;
    }

    // Formatar o CPF para o padrão '000.000.000-00'
    $cpfFormatado = sprintf(
        '%s.%s.%s-%s',
        substr($cpf, 0, 3),
        substr($cpf, 3, 3),
        substr($cpf, 6, 3),
        substr($cpf, 9, 2)
    );

    return $cpfFormatado;
}



function isCnpjValid($cnpj)
{
    $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);

    if (strlen($cnpjLimpo) !== 14 || preg_match('/^0+$/', $cnpjLimpo)) {
        return false;
    }

    $calculatedChecksum = function ($cnpj, $positions) {
        $sum = 0;
        $length = count($positions);

        for ($i = 0; $i < $length; $i++) {
            $sum += intval($cnpj[$i]) * $positions[$i];
        }

        $remainder = $sum % 11;
        return ($remainder < 2) ? 0 : (11 - $remainder);
    };

    $digitoVerificador1 = $calculatedChecksum($cnpjLimpo, [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
    $digitoVerificador2 = $calculatedChecksum($cnpjLimpo, [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);

    return ($cnpjLimpo[12] == $digitoVerificador1 && $cnpjLimpo[13] == $digitoVerificador2);
}


function isCpfValid($cpf)
{
    $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpfLimpo) !== 11 || preg_match('/(\d)\1{10}/', $cpfLimpo)) {
        return false;
    }

    $calculatedChecksum = function ($cpf, $positions) {
        $sum = 0;
        $length = count($positions);

        for ($i = 0; $i < $length; $i++) {
            $sum += intval($cpf[$i]) * $positions[$i];
        }

        $remainder = $sum % 11;
        return ($remainder < 2) ? 0 : (11 - $remainder);
    };

    $digitoVerificador1 = $calculatedChecksum($cpfLimpo, [10, 9, 8, 7, 6, 5, 4, 3, 2]);
    $digitoVerificador2 = $calculatedChecksum($cpfLimpo, [11, 10, 9, 8, 7, 6, 5, 4, 3, 2]);

    return ($cpfLimpo[9] == $digitoVerificador1 && $cpfLimpo[10] == $digitoVerificador2);
}
