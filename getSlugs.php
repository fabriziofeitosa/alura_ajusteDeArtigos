<?php header('Content-type: application/json');
// Ler planilha com os links
$planilha = fopen("urls.csv", 'r');

$slugs = [];
$n = 0;

while(!feof($planilha)) {
    $n++;

    $linha = fgets($planilha, 1024);
    $item = explode(',', $linha);

    // regex
    // $pattern = '/^(.*\/artigos\/)(.*)$/';
    $pattern = '/^(.*\/artigos\/)([-\w]+)(\??.*)$/';

    // somente o slug
    $item[0] = preg_replace($pattern, '$2', $item[0]);

    // salvar no array de slugs;
    array_push($slugs, $item[0]);

    // ! Limitador para teste
    // if( $n >= 50 ) break;
}

// Fecha arquivo aberto
fclose($planilha);

// ! Forçando um slug
// array_push($slugs, 'domain-driven-design-no-falando-em-java-2008');
// array_push($slugs, 'account-based-marketing');
// array_push($slugs, 'a-importancia-da-capacitacao-para-o-trabalho-como-freelancer');
// array_push($slugs, 'o-que-e-html-suas-tags-parte-1-estrutura-basica');

// Criando fatias
$fatias = array_chunk($slugs, 100);

echo json_encode( $fatias );

exit();