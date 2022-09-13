<?php header('Content-type: application/json');

// Pasta onde será salvo
$folder = 'logs';

// Verificar se a pasta existe ou cria ela
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

// Receber nome
$name = $_POST['name'] ?? false;
if( !$name ) {
    echo json_encode( false );
    exit();
}

// Receber array
$array = json_decode( $_POST['array'] ) ?? false;
if( !$array ) {
    echo json_encode( false );
    exit();
}

// Apagar anterior se existir
if (file_exists("$folder/$name.txt")) {
    unlink("$folder/$name.txt");
}

// Criar arquivo com os dados
file_put_contents("$folder/$name.txt", print_r($array, true));

echo json_encode( true );

exit();