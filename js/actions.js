$(function () {

    // ? Promise de espera
    const sleep = m => new Promise(r => setTimeout(r, m));

    // ? Ação Principal
    $("#formPrincipal").submit(async function (event) {
        event.preventDefault();

        console.log("SCRIPT INICIADO");

        var notFound = [],
            notFoundQtd = 0;

        var duplicate = [],
            duplicateQtd = 0;

        var postWithSameLevels = [],
            debugArray = [];

        var success = [],
            successQtd = 0;

        var blanks = [],
            blanksQtd = 0;

        // Fatias de Slugs
        var fatias = await getSlugs();

        console.log("COPIANDO ARQUIVOS");

        // Copiar arquivos antes de editar
        for (const [index, slugs] of fatias.entries()) {
            // ? Esperar alguns segundos para fazer de novo caso não seja a primeira vez
            if (index != 0) await sleep(2 * 1000);

            // Copiar
            resultCopy = await copyFiles( slugs, index );
            if( !resultCopy ) continue;

            // Jogar nas variáveis finais
            if( resultCopy['notFound'] ) {
                // Contador
                notFoundQtd += resultCopy['notFound'].length;
                await $('.notFound .qtd').text(notFoundQtd);
                // Loop
                await resultCopy['notFound'].forEach(async element => {
                    notFound.push( element );
                    await $('.notFound').append(`<li>O arquivo <kbd>${element}.md</kbd> não foi encontrado.</li>`);
                });
            }
            if( resultCopy['duplicate'] ) {
                // Contador
                duplicateQtd += resultCopy['duplicate'].length;
                await $('.duplicate .qtd').text(duplicateQtd);
                // Loop
                await resultCopy['duplicate'].forEach(async element => {
                    duplicate.push( element );
                    await $('.duplicate').append(`<li>O arquivo <kbd>${element}.md</kbd> está duplicado.</li>`);
                });
            }

            // ? Ajustar fatia sem elementos duplicados
            fatias[index] = [];
            fatias[index] = resultCopy['slugs'];

        }

        // ! Relatar
        await createLog( 'notFound', notFound );
        await createLog( 'duplicate', duplicate );

        console.log("COPIANDO ARQUIVOS");

        // Percorrer as fatias para editar
        for (const [index, slugs] of fatias.entries()) {
            // ? Esperar alguns segundos para fazer de novo caso não seja a primeira vez
            if (index != 0) await sleep(4 * 1000);

            // Gerar os arquivos e ter um retorno sobre
            var resultadoFinal = await editFiles( slugs, index );
            if( !resultadoFinal ) continue;

            if (resultadoFinal.error) {
                console.log(`Ocorreu um erro nessa fatia de index: ${index}`);
                continue;
            };

            // Escrever modificados
            if( resultadoFinal['success'] ) {
                await resultadoFinal['success'].forEach(async element => {
                    success.push( element );
                    await $('.success').append(`<li>O arquivo <kbd>${element}.md</kbd> foi ajustado.</li>`);
                });
                // Contador
                successQtd += resultadoFinal['success'].length;
                await $('.success .qtd').text(successQtd);
            }

            // Escrever não modificados
            if( resultadoFinal['blanks'] ) {
                await resultadoFinal['blanks'].forEach(async element => {
                    blanks.push( element );
                    await $('.blanks').append(`<li>O arquivo <kbd>${element}.md</kbd> não precisou de ajuste.</li>`);
                });
                // Contador
                blanksQtd += resultadoFinal['blanks'].length;
                await $('.blanks .qtd').text(blanksQtd);
            }

            // ! Informar quais tem mesmo level em seus headers
            if( resultadoFinal['postWithSameLevels'] ) {
                resultadoFinal['postWithSameLevels'].forEach(element => {
                    postWithSameLevels.push( element );
                });
            }

            // ! Debug
            if( resultadoFinal['debugArray'] ) {
                resultadoFinal['debugArray'].forEach(element => {
                    debugArray.push( element );
                });
            }
        }

        // ! Relatar
        await createLog( 'success', success );
        await createLog( 'blanks', blanks );
        await createLog( 'postWithSameLevels', postWithSameLevels );
        await createLog( 'debugArray', debugArray );

        console.log("FIM");

    });

    // ? Pegar os slugs
    async function getSlugs() {
        console.log("run: getSlugs");
        var retorno = '';

        await $.ajax({
            url: 'getSlugs.php',
            method: 'POST',
            success: function (result) {
                // console.log( result );
                retorno = result;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                retorno = {
                    jqXHR,
                    textStatus,
                    errorThrown
                }
                console.log(retorno);
            }
        });

        console.log("end: getSlugs");

        return retorno;
    }

    // ? Copiar arquivos
    async function copyFiles( slugs, index ) {
        console.log(`run: copyFiles | index: ${index}`);
        var retorno = '';

        var jsonString = JSON.stringify( slugs );

        await $.ajax({
            url: 'copyFiles.php',
            method: 'POST',
            data: {
                slugs: jsonString,
            },
            success: function (result) {
                // console.log( result );
                retorno = result;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                retorno = {
                    jqXHR,
                    textStatus,
                    errorThrown
                }
                console.log(retorno);
            }
        });

        console.log(`end: copyFiles | index: ${index}`);

        return retorno;
    }

    // ? Gerar arquivos
    async function editFiles( slugs, index ) {
        console.log(`run: editFiles | index: ${index}`);
        var retorno = '';

        var jsonString = JSON.stringify( slugs );

        await $.ajax({
            url: 'editFiles.php',
            method: 'POST',
            data: {
                slugs: jsonString,
            },
            success: function (result) {
                // console.log( result );
                retorno = result;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                retorno = {
                    jqXHR,
                    textStatus,
                    errorThrown
                }
                console.log(retorno);
            }
        });

        console.log(`end: editFiles | index: ${index}`);

        return retorno;
    }

    // ? Gerar arquivos de texto com relatórios
    async function createLog( name, array ) {
        console.log(`run: createLog '${name}'`);
        var retorno = '';

        var jsonString = JSON.stringify( array );

        await $.ajax({
            url: 'createLog.php',
            method: 'POST',
            data: {
                name: name,
                array: jsonString,
            },
            success: function (result) {
                // console.log( result );
                if( result ) console.log("---------- Criado!");
                else console.log("---------- Não foi criado");
                retorno = result;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                retorno = {
                    jqXHR,
                    textStatus,
                    errorThrown
                }
                console.log(retorno);
            }
        });

        console.log(`end: createLog`);
    }
});