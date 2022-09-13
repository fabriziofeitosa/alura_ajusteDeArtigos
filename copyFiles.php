<?php header('Content-type: application/json');

// Pasta onde será movido
$folder = 'copias';

// Salvar alguns testes
$notFound = array();
$duplicate = array();

// Verificar se a pasta existe ou cria ela
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

// Receber slugs
$slugs = json_decode( $_POST['slugs'] ) ?? false;
if( !$slugs ) {
    echo json_encode( false );
    exit();
}

// Percorrer os slugs para edição
foreach ($slugs as $key => $slug) {

    // Verificar se o arquivo original existe
    if ( !file_exists("../$slug.md") ) {
        // Salvar o nome desse que não existe
        array_push( $notFound, $slug );
        // Remover do array inicial
        unset($slugs[$key]);
        // Pular para o próximo
        continue;
    }

    // Verificar se já foi copiado
    if ( file_exists("$folder/$slug.md") ) {
        // Salvar o nome desse duplicado
        array_push( $duplicate, $slug );
        // Remover do array inicial
        unset($slugs[$key]);
        // Pular para o próximo
        continue;
    }

    // Copiar arquivo
    copy("../$slug.md", "$folder/$slug.md");

}

echo json_encode( array(
    'slugs' => $slugs,
    'notFound' => count($notFound) > 0 ? $notFound : false,
    'duplicate' => count($duplicate) > 0 ? $duplicate : false,
) );

exit();