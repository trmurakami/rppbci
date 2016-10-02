<?php

function query_elastic ($query,$server) {
    $ch = curl_init();
    $method = "POST";
    $url = "http://$server/rppbci/journals/_search";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, 9200);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, TRUE);
    return $data;
}

function contar_registros ($server) {
    $ch = curl_init();
    $method = "POST";
    $url = "http://$server/rppbci/journals/_count";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, 9200);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, TRUE);
    return $data["count"];
}

function ultimos_registros($server) {
    
     $query = '{
                "query": {
                    "match_all": {}
                 },
                 "filter":{
                    "bool":{
                        "must_not" : {
                            "term": {"status":"deleted"}
                        }
                    }
                 },                 
                "size": 5,
                "sort" : [
                    {"_uid" : {"order" : "desc"}}
                    ]
                }';
    $data = query_elastic($query,$server);

    foreach ($data["hits"]["hits"] as $r){
        print_r($r);
    
        echo '<article class="uk-comment">
        <header class="uk-comment-header">';    
        //if (!empty($r["_source"]['unidadeUSP'])) {
        //$file = 'inc/images/logosusp/'.$r["_source"]['unidadeUSP'][0].'.jpg';
        //}
        //if (file_exists($file)) {
        //echo '<img class="uk-comment-avatar" src="'.$file.'">';
        //} else {
        //#echo ''.$r['unidadeUSP'].'</a>';
        //};
        //if (!empty($r["_source"]['title'])){
        //echo '<a class="ui small header" href="single.php?_id='.$r['_id'].'"><h4 class="uk-comment-title">'.$r["_source"]['title'].' ('.$r["_source"]['year'].')</h4></a>';
        //};
        //echo '<div class="extra">';
        //if (!empty($r["_source"]['authors'])) {
        //echo '<div class="uk-comment-meta";">';    
        //foreach ($r["_source"]['authors'] as $autores) {
        //echo '<a href="result.php?authors[]='.$autores.'">'.$autores.'</a>, ';
        //}
        //echo '</div>';     
        //};
        echo '</header>';
        echo '</article>';
    }     
}

?>