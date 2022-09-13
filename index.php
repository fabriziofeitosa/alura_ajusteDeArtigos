<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Convert Cosmos headers</title>
    </head>
    <body>

    <style>
        body {
            font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans","Liberation Sans",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
            line-height: 1.2;
        }
        kbd {
            padding: 0.1875rem 0.375rem;
            font-size: .875em;
            color: #fff;
            background-color: #212529;
            border-radius: 0.25rem;
        }
        .box {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #212529;
        }
        #resultados {
            display: flex;
            gap: 1rem;
        }

        .box ul {
            padding: 0;
            list-style: none;
            width: 100%;
        }
        .box li {
            padding: 10px 0;
        }
    </style>

    <h1>Ação de ajustar arquivos do Cosmos.</h1>

    <form action="" id="formPrincipal">
        <button type="submit">Organizar arquivos</button>
    </form>

    <div id="resultados" class="box">
        <ul class="notFound">
            <h2>Não encontrados: (<span class="qtd">0</span>)</h2>
        </ul>
        <ul class="duplicate">
            <h2>Duplicados (<span class="qtd">0</span>)</h2>
        </ul>
        <ul class="success">
            <h2>Editados (<span class="qtd">0</span>)</h2>
        </ul>
        <ul class="blanks">
            <h2>Não editados: (<span class="qtd">0</span>)</h2>
        </ul>
    </div>

    <script src="js/jquery-3.6.1.min.js"></script>
    <script src="js/actions.js"></script>

    </body>
</html>