<?php

if( isset($_GET['url']) ){

    $url = $_GET['url'];

    $metodo = $_GET['metodo'];
    $tiporetorno = $_GET['tiporetorno'];
    $tipoheader = $_GET['tipoheader'];
    $headers = $_GET['headers'];
    $tipodados = $_GET['tipodados'];
    $dados = $_GET['dados'];
    $dados_array = array();

    $ch = curl_init();


    if ($headers != '') {
        $cabecalho = array();
        if ($tipoheader == 'json') {
            $cabecalho_array = json_decode($headers, true);
            foreach ($cabecalho_array as $key => $linhas) {
                $cabecalho[] = trim($key) . ': ' . trim($linhas);
            }

        } elseif ($tipoheader == 'space') {
            $cabecalho_array = explode("\n", $headers);

            foreach ($cabecalho_array as $linhas) {
                $pos_space = strpos(trim($linhas), ' ');
                $cabecalho[] = substr(trim($linhas), 0, $pos_space) . ': ' . substr(trim($linhas), $pos_space+1);
            }
        } else {
            $cabecalho_array = explode("\n", $headers);

            foreach ($cabecalho_array as $linhas) {
                $cabecalho[] = trim($linhas);
            }

        }
        if (count($cabecalho) > 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
        }
    }
    if ($dados != '') {
        if ($tipodados == 'json') {
            $dados_array = $dados;
        } elseif ($tipodados == 'space') {
            $dados_linha = explode("\n", $dados);
            foreach ($dados_linha as $linhas) {
                $dados_pos = strpos(trim($linhas), ' ');
                $dados_array[substr(trim($linhas), 0, $dados_pos)] = substr(trim($linhas), $dados_pos+1);

            }
        }

        else {
            $dados_linha = explode("\n", $dados);
            foreach ($dados_linha as $linhas) {
                $dados_pos = strpos(trim($linhas), ':');
                $dados_array[substr(trim($linhas), 0, $dados_pos)] = substr(trim($linhas), $dados_pos+1);

            }
        }
    }


    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    switch ($metodo) {
        case 'get':
            $campos = $dados_array;
            break;
        case 'post':
            $campos = $dados_array;
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
            break;
        default:
            $campos = json_encode($dados_array);
            if ($tipodados == 'json') {
                $campos = $dados;
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($metodo));
    }


    $resposta = curl_exec($ch);

    curl_close($ch);

    if ($tiporetorno == 'json') {
        echo '<pre><div>' . $resposta . '</div></pre>';
    } else {
        echo '<div>' . $resposta . '</div>';
    }


}
else{

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Klbrate: envio de cabeçalhos</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100%;
            color: #151b1e;
        }

        header {
            width: 100%;
            background-color: #333333;
            background-image: url('data:image/png;base64,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');
            background-position: 20px center;
            background-repeat: no-repeat;
            height: 60px;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 40px;
            color: #eee;
            background-color: #000;
            line-height: 40px;
            text-align: center;
        }

        #envio, #retorno {

            margin: 0;
            padding: 0;
            height: calc(100vh - 100px);
            width: 50%;
            padding-bottom: 50px;

        }

        main {
            display: flex;
            flex-grow: 0;
            flex-shrink: 0;

        }

        #envio div {
            margin-top: 20px;
        }

        label {
            margin-bottom: 0;
            text-align: right;
            padding-right: 15px;
            padding-left: 2px;
            font-size: 1rem;
            display: inline-block;
            width: 180px;
        }

        textarea, input, select {
            border-radius: .25rem;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            padding: 6px 12px;
        }

        textarea {
            width: calc(100% - 400px);
            height: 100px;
        }

        #url {
            width: calc(100% - 330px);
        }

        @media (max-width: 1199.98px) {
            textarea {
                width: 100%;
            }

            #url {
                width: 100%;
            }
        }

        .botao {
            margin-top: 30px;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            font-size: 0.875rem;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            padding: .375rem .75rem;
            cursor: pointer;
        }

        #eframe {
            margin: 0;
            padding: 0;
            height: calc(100vh - 210px);
            width: 100%;
            border: 0;

        }

    </style>
</head>
<body>

<header>

</header>
<main>

    <div id="envio">
        <h1>Dados para envio</h1>
        <form action="painel.php" method="get" target="eframe">
            <div>
                <label>URL do envio</label>
                <input type="text" name="url" id="url">
            </div>
            <div>
                <label>Método</label>
                <select name="metodo" id="metodo">
                    <option value="get">GET</option>
                    <option value="post">POST</option>
                    <option value="delete">DELETE</option>
                    <option value="put">PUT</option>
                </select>
            </div>
            <div>
                <label>Retorno</label>
                <select name="tiporetorno" id="tiporetorno">
                    <option value="json">JSON</option>
                    <option value="text">TEXTO</option>
                </select>
            </div>
            <div>
                <label>Requisição do header</label>
                <select name="tipoheader" id="tipoheader">
                    <option value="text">TEXTO COM :</option>
                    <option value="json">JSON</option>
                    <option value="space">TEXTO COM " "</option>
                </select>
                <textarea name="headers" id="headers"></textarea>

            </div>
            <div>
                <label>Envio de paramêtros</label>
                <select name="tipodados" id="tipodados">
                    <option value="json">JSON</option>
                    <option value="text">TEXTO COM :</option>
                    <option value="space">TEXTO COM " "</option>
                </select>
                <textarea name="dados" id="dados"></textarea>

            </div>
            <div>
                <label> </label>
                <input class="botao" type="submit" id="enviar" value="Enviar dados">

            </div>
        </form>

    </div>
    <div id="retorno">
        <h1>Dados para retorno</h1>
        <iframe id="eframe" name="eframe">
        </iframe>
    </div>
</main>

<footer>
    Klbrate © 2021 todos direitos reservados
</footer>


</body>
</html><?php


}


?>