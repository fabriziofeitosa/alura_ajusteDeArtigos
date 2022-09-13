<?php header('Content-type: application/json');

// Pasta das copias
$copyFolder = 'copias';

// Pasta onde será salvo o que for convetido
$folder = 'convertidos';

// Verificar se a pasta existe ou cria ela
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

// Pra salvar o que vai ser retornado
$success = array();
$blanks = array();

// Receber slugs
$slugs = json_decode( $_POST['slugs'] ) ?? false;
if( !$slugs ) {
    echo json_encode( false );
    exit();
}

// Salvar posts que tem níveis iguais
$postWithSameLevels = array();

// ! REMOVER!
$debugArray = array();

// Percorrer para verificar os níveis dos headers
foreach ($slugs as $key => $slug) {

    // Verificar se o arquivo existe
    if ( !file_exists("$copyFolder/$slug.md") ) continue;

    // Abrir arquivo
    $reading = fopen("$copyFolder/$slug.md", 'r');

    // Procurar no inicio # de 2 a 6
    $patternLine = '/(^#{2,6} )(.*)/';

    // Salvar level
    $levelSharp = false;
    // Mesmo level?
    $sameLevel = true;

    // Lendo
    while (!feof($reading)) {

        // Lendo a linha
        $line = fgets($reading);

        // Verificar se na linha tem o que busco
        if (preg_match( $patternLine, $line )) {
            // String com a primeira parte, as #
            $sharp = trim( preg_replace($patternLine, "$1", $line) );

            // ! REMOVER
            /* if( $slug == 'o-que-e-html-suas-tags-parte-1-estrutura-basica' ) {
                array_push( $debugArray, array(
                    'linha' => $line,
                    'level' => $levelSharp,
                    'sameLevel' => $sameLevel
                ) );
            } */
            // ! REMOVER

            // Verifica se foi definido algum level
            if( $levelSharp === false ) {
                // salvar Level / Tamanho
                $levelSharp = strlen($sharp);
                // Pular para o próximo
                continue;
            }

            // Comparar com o último level salvo
            if( strlen($sharp) != $levelSharp) {
                $sameLevel = false;
                // Se tiver um diferente nem
                // precisa olhar os outros
                break;
            }

        }
    }

    // Se terminar positivo é pq o post tem
    // um só level em seus headers
    if( $sameLevel ) {
        array_push($postWithSameLevels, $slug);
    }

    // Fechar arquivos
    fclose($reading);
}

// Percorrer os slugs para edição
foreach ($slugs as $key => $slug) {

    // Verificar se o arquivo existe
    if ( !file_exists("$copyFolder/$slug.md") ) continue;

    // Abrir arquivo
    $reading = fopen("$copyFolder/$slug.md", 'r');
    // Criar temporário
    $writing = fopen("$folder/myfile_$key.tmp", 'w');

    // Procurar no inicio # de 2 a 6
    $patternLine = '/(^#{2,6} )(.*)/';

    // Essa variável vai ser usada para verificar se foi alterado algo
    $replaced = false;

    // Lendo
    while (!feof($reading)) {

        // Lendo a linha
        $line = fgets($reading);

        // Verificar se na linha tem o que busco
        if (preg_match( $patternLine, $line )) {
            // String com a primeira parte, as #
            $sharp = trim( preg_replace($patternLine, "$1", $line) );

            // Verificar se esse artigo está no meio
            // dos que tem o mesmo level em todos
            // os seus headers
            if( in_array( $slug, $postWithSameLevels ) ) {
                // Deixandos os headers no nível 1
                $sharp = "#";
            } else {
                // Remove primeiro caracterie para
                // baixar o level do header
                $sharp = substr($sharp, 1);
            }

            // String com o título
            $title = preg_replace($patternLine, "$2", $line);

            // Forma a nova linha
            $line = "$sharp $title";

            // Confirmar que houve mudanças
            $replaced = true;
        }

        // Escreve no arquivo temporário
        fputs($writing, $line);
    }

    // Fechar arquivos
    fclose($reading);
    fclose($writing);

    // Só vai substituir se tiver ocorrido algum ajuste
    if ($replaced) {
        // Muda o nome pq houve mudanças
        rename("$folder/myfile_$key.tmp", "$folder/$slug.md");
        // Arquivos que foram ajustados
        array_push( $success, $slug );
    } else {
        // Apaga temporário pq não houve mudança
        unlink("$folder/myfile_$key.tmp");
        // Arquivos que não precisaram de ajustes
        array_push( $blanks, $slug );
    }

}

echo json_encode( array(
    'success' => count($success) > 0 ? $success : false,
    'blanks' => count($blanks) > 0 ? $blanks : false,
    'postWithSameLevels' => count($postWithSameLevels) > 0 ? $postWithSameLevels : false,
    'debugArray' => count($debugArray) > 0 ? $debugArray : false,
) );

exit();