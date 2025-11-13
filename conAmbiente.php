<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once 'conexao.php';
// Define o charset da conexão para garantir o suporte a UTF-8
$con->set_charset("utf8");

// Decodifica a entrada JSON (mantido por compatibilidade, mas pode ser removido)
json_decode(file_get_contents('php://input'), true);

// *** Alteração: Nova SQL para selecionar os campos da tabela 'ambiente' ***
$sql = "SELECT idAmbiente, nmAmbiente FROM ambiente ORDER BY nmAmbiente";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    // Se houver resultados, adiciona cada linha ao array de resposta
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    // *** Alteração: Estrutura de resposta vazia adaptada para 'ambiente' ***
    // Se não houver resultados, retorna uma estrutura vazia padrão
    $response[] = [
        "idAmbiente" => 0,
        "nmAmbiente" => ""
    ];
}

// Define o cabeçalho como JSON e especifica o charset UTF-8
header('Content-Type: application/json; charset=utf-8');
// Codifica o array de resposta para JSON, preservando caracteres UTF-8
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Fecha a conexão com o banco de dados
$con->close();
?>