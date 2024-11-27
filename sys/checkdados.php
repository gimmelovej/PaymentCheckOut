<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

if(isset($_POST['cep']) AND $_POST['cep'] != "")
{
    $_POST['cep'] = str_replace("-","",str_replace("e","",$_POST['cep']));
    $url = 'http://viacep.com.br/ws/'.$_POST['cep']."/xml/";
    $xml = simplexml_load_file($url);

    $retorno = array("cidade"=>strval($xml->localidade),"logradouro"=>strval($xml->logradouro),"bairro"=>strval($xml->bairro),"estado"=>strval($xml->uf));
    echo json_encode($retorno);
    exit;
}