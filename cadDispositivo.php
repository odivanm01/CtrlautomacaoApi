<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho para resposta JSON
header('Content-Type: application/json');

// Inclui a conexão compartilhada com o banco de dados
// Certifique-se de que 'conexao.php' está configurado corretamente
require_once 'conexao.php';
$con->set_charset("utf8");

// Obtém e decodifica o input JSON
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!$jsonParam) {
    echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos ou ausentes.']);
    exit;
}

// Extrai e valida os dados para a tabela 'dispositivo'
// Os campos da tabela são: Estado, Grupo, Ambiente, Tipo, idAmbiente
$Estado     = trim($jsonParam['Estado'] ?? '');
$Grupo      = trim($jsonParam['Grupo'] ?? '');
$Ambiente   = trim($jsonParam['Ambiente'] ?? '');
$Tipo       = trim($jsonParam['Tipo'] ?? '');
$idAmbiente = intval($jsonParam['idAmbiente'] ?? 0); // Espera-se um inteiro


// Prepara a consulta SQL para inserção
$stmt = $con->prepare("
    INSERT INTO dispositivo (Estado, Grupo, Ambiente, Tipo, idAmbiente)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta: ' . $con->error]);
    exit;
}

// Associa os parâmetros (bind_param)
// Tipos de dados:
// Estado: char(1) -> 's' (string)
// Grupo: varchar(45) -> 's' (string)
// Ambiente: varchar(45) -> 's' (string)
// Tipo: varchar(45) -> 's' (string)
// idAmbiente: int -> 'i' (integer)
$stmt->bind_param("ssssi", $Estado, $Grupo, $Ambiente, $Tipo, $idAmbiente);

// Executa e retorna o resultado
if ($stmt->execute()) {
    // Retorna o ID do novo dispositivo inserido (se necessário)
    $newId = $con->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Dispositivo inserido com sucesso!',
        'IpDispositivo' => $newId
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro no registro do dispositivo: ' . $stmt->error]);
}

// Fecha a declaração e a conexão
$stmt->close();
$con->close();

?>