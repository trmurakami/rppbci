<?php

// Set directory to ROOT
chdir('../');
// Include essencial files
require 'inc/functions.php';

/* Exibir erros */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);


$query["query"]["query_string"]["query"] = '_exists_:doi doi:1* -_exists_:crossref';

$params = [];
$params["index"] = $index;
$params["size"] = $_GET["size"];
$params["body"] = $query;

$cursor = $client->search($params);

foreach ($cursor["hits"]["hits"] as $r) {

    //print("<pre>".print_r($r, true)."</pre>");

    $clientCrossref = new RenanBr\CrossRefClient();
    $clientCrossref->setUserAgent('GroovyBib/1.1 (http://tecbib.com/rppbci/; mailto:trmurakami@gmail.com)');
    $exists = $clientCrossref->exists('works/'.$r["_source"]["doi"].'');
    if ($exists == true) {
        $work = $clientCrossref->request('works/'.$r["_source"]["doi"].'');
        //print("<pre>".print_r($work, true)."</pre>");
        //echo "<br/><br/><br/><br/>";
        $body["doc"]["crossref"] = $work;
        $body["doc_as_upsert"] = true;
        $resultado_crossref = Elasticsearch::update($r["_id"], $body);
        //print_r($resultado_crossref);
        sleep(11);
        ob_flush();
        flush();          
    } else {
        $body["doc"]["crossref"]["notFound"] = true;
        $body["doc_as_upsert"] = true;
        $resultado_crossref = Elasticsearch::update($r["_id"], $body);
        //print_r($resultado_crossref);
        sleep(2);
        ob_flush();
        flush();
    }    

}

header("Refresh: 0");

?>