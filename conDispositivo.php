<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once 'conexao.php';
// Define o charset da conexão para garantir o suporte a UTF-8
$con->set_charset("utf8");

// Decodifica a entrada JSON (mas ignora seu conteúdo, mantendo a estrutura original)
// Pode ser removido se não houver expectativa de dados via POST/PUT
json_decode(file_get_contents('php://input'), true);

// Nova SQL: Seleciona todos os campos da tabela 'dispositivo'
$sql = "SELECT IpDispositivo, Estado, Grupo, Ambiente, Tipo, idAmbiente FROM dispositivo";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    // Se houver resultados, adiciona cada linha ao array de resposta
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    // Se não houver resultados, retorna uma estrutura vazia padrão
    $response[] = [
        "IpDispositivo" => 0,
        "Estado" => "",
        "Grupo" => "",
        "Ambiente" => "",
        "Tipo" => "",
        "idAmbiente" => 0
    ];
}

// Define o cabeçalho como JSON e especifica o charset UTF-8
header('Content-Type: application/json; charset=utf-8');
// Codifica o array de resposta para JSON, preservando caracteres UTF-8
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Fecha a conexão com o banco de dados
$con->close();
?>