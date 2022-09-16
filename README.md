# Ajuste de artigos da Alura

Fiz esse script para ler a tabela [urls.csv](https://github.com/fabriziofeitosa/alura_ajusteDeArtigos/blob/main/urls.csv) que contem artigos que precisavam ter seus headers ajustados. Foi discutido o comportamento adequado a se fazer com os casos encontrados:

1. Caso o artigo tenhas seus headers todos iguais, jogar em `h1` que corresponde a somente um `#`.
2. Ou, diminuir os headers em um `#`;

Para não disparar o tempo do PHP, usei `jQuery` para 'fatiar' a lista e passar os slugs com um certo intervalo, depois enviando para o PHP fazer a 'mágica'.
