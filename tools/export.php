<?php

$file = "export_rppbci.tsv";
header('Content-type: text/tab-separated-values; charset=utf-8');
header("Content-Disposition: attachment; filename=$file");

// Set directory to ROOT
chdir('../');
// Include essencial files
include 'inc/config.php';

/* Exibir erros */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);


$query["query"]["bool"]["must"]["query_string"]["query"] = "*";
$query["query"]["bool"]["must_not"]["term"]["type.keyword"] = "journal";

$params = [];
$params["index"] = $index;
$params["size"] = 50;
$params["scroll"] = "30s";
$params["body"] = $query;

$cursor = $client->search($params);

/* Header */
$content[] = "Periódico\tSet\tID\tTítulo\tAutores\tNúm. de autores\tAno\tURL Principal\tFacebook Total\tFacebook Comentários\tFacebook Compartilhamentos\tFacebook Reações\tData de coleta do Facebook";
/* /Header */

foreach ($cursor["hits"]["hits"] as $r) {

    foreach ($r["_source"]['autores'] as $autor) {
        $autores_array[]= $autor["nomeCompletoDoAutor"];
    }

    $fields[] = $r["_source"]['source'];
    if (isset($r["_source"]['set'])) {
        $fields[] = $r["_source"]['set'];
    } else {
        $fields[] = "";
    }   
    $fields[] = $r["_source"]['harvester_id'];
    $fields[] = $r["_source"]['titulo'];
    $fields[] = implode("|", $autores_array);
    $fields[] = $r["_source"]['numAutores'];
    $fields[] = $r["_source"]['datePublished'];
    $fields[] = $r["_source"]['url'];
    $fields[] = $r["_source"]['facebook']['facebook_total'];
    $fields[] = $r["_source"]['facebook']['comment_count'];
    $fields[] = $r["_source"]['facebook']['share_count'];
    $fields[] = $r["_source"]['facebook']['reaction_count'];
    $fields[] = $r["_source"]['facebook']['date'];

    $content[] = implode("\t", $fields);
    unset($autores_array);
    unset($fields);    
}

while (isset($cursor['hits']['hits']) && count($cursor['hits']['hits']) > 0) {
    $scroll_id = $cursor['_scroll_id'];
    $cursor = $client->scroll(
        [
        "scroll_id" => $scroll_id,
        "scroll" => "30s"
        ]
    );

    foreach ($cursor["hits"]["hits"] as $r) {

        foreach ($r["_source"]['autores'] as $autor) {
            $autores_array[]= $autor["nomeCompletoDoAutor"];
        }
    
        $fields[] = $r["_source"]['source'];
        if (isset($r["_source"]['set'])) {
            $fields[] = $r["_source"]['set'];
        } else {
            $fields[] = "";
        }        
        $fields[] = $r["_source"]['harvester_id'];
        $fields[] = $r["_source"]['titulo'];
        $fields[] = implode("|", $autores_array);
        $fields[] = $r["_source"]['numAutores'];
        $fields[] = $r["_source"]['datePublished'];
        $fields[] = $r["_source"]['url'];
        $fields[] = $r["_source"]['facebook']['facebook_total'];
        $fields[] = $r["_source"]['facebook']['comment_count'];
        $fields[] = $r["_source"]['facebook']['share_count'];
        $fields[] = $r["_source"]['facebook']['reaction_count'];
        $fields[] = $r["_source"]['facebook']['date'];
    
        $content[] = implode("\t", $fields);
        unset($autores_array);
        unset($fields);

    }
}

echo implode("\n", $content);

?>