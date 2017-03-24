<?php

include('functions_core.php');

class inicio {
    
    static function contar_registros ($server) {
        global $index;
        global $type;
        global $client;
        
        $body = '
            {
                "query": {
                    "match_all": {}
                }
            } 
        ';
        
        $params = [];
        $params["index"] = $index;
        $params["type"] = $type;
        $params["size"] = 0;
        $params["body"] = $body;
        
        $response = $client->search($params);
        return $response["hits"]["total"];

    } 
    
    static function top_registros() {
        global $index;
        global $type;
        global $client;
        $query = '{
                    "query": {
                        "match_all": {}
                     },                
                    "sort" : [
                        {"facebook.facebook_total" : {"order" : "desc"}}
                        ]
                    }';
        
        $params = [];
        $params["index"] = $index;
        $params["type"] = $type;
        $params["size"] = 10;
        $params["body"] = $query;        
        
        $data = $client->search($params);  

        //print_r($data);

        foreach ($data["hits"]["hits"] as $r){
            //var_dump($r);

            echo '<article class="uk-comment">
            <header class="uk-comment-header">'; 
            //print_r($r);
            echo '<a class="ui small header" href="'.$r['_source']['url_principal'].'"><h4 class="uk-comment-title">'.$r['_source']['titulo'].' ('.$r['_source']['ano'].' - '.$r['_source']['source'].') - <b>'.$r['_source']['facebook']['facebook_total'].' interações</b></h4></a>';
            echo '<div class="extra">';
            if (!empty($r["_source"]['creator'])) {
                echo '<div class="uk-comment-meta";">';    
                foreach ($r["_source"]['autores'] as $autores) {                
                echo '<a href="result.php?search[]=creator.keyword:&quot;'.$autores[0].'&quot;">'.$autores[0].'</a>, ';
                }
                echo '</div>';
            }
            echo '</header>';
            echo '</article>';
        }     
    }    
    
    
    /*Facetas - Página inicial*/
    static function facetas_inicio($field) {
        global $index;
        global $type;
        global $client;

        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        $query["aggs"]["counts"]["terms"]["size"] = 1000;

        $params = [];
        $params["index"] = $index;
        $params["type"] = $type;
        $params["size"] = 10;
        $params["body"] = $query;           

        $data = $client->search($params);


        foreach ($data["aggregations"]["counts"]["buckets"] as $facets) {
            echo '<div class="uk-width-medium-1-5"><div class="uk-panel uk-panel-hover" data-my-category="'.$facets['key'][0].'" data-my-category2="'.$facets['doc_count'].'"><p><i class="uk-icon-bookmark"></i> <a href="result.php?&search[]=+'.$field.'.keyword:&quot;'.$facets['key'].'&quot;">'.$facets['key'].' ('.number_format($facets['doc_count'],0,',','.').')</a></p></div></div>';
        }

    } 
     
    
}

class admin {
    
    /*Facetas - Página inicial*/
    static function sources($field) {
        global $index;
        global $type;
        global $client;

        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        $query["aggs"]["counts"]["terms"]["size"] = 1000;

        $params = [];
        $params["index"] = $index;
        $params["type"] = "repository";
        $params["size"] = 100;
        $params["body"] = $query;           

        $data = $client->search($params);
        //print_r($data["hits"]["hits"]);

        echo '<table class="uk-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>URL</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($data["hits"]["hits"] as $repository) {
            //print_r($repository);
            echo '<tr><td>'.$repository['_id'].'</td><td>'.$repository['_source']['name'].'</td><td>';
	    echo admin::count_records($repository['_source']['name']);
	    echo '</td><td><a class="uk-button uk-button-success" href="http://bdpife2.sibi.usp.br/rppbci/harvester.php?oai='.$repository['_source']['url'].'">Update</a></td><td><a class="uk-button uk-button-danger" href="http://bdpife2.sibi.usp.br/rppbci/harvester.php?delete='.$repository['_id'].'&delete_name=&quot;'.$repository['_source']['name'].'&quot;">Excluir</a></td></tr>';
            
	}
        echo '</tbody>';
        echo '</table>';

    }

    static function count_records ($name) {
	global $type;
	$body["query"]["query_string"]["query"] = 'source.keyword:"'.$name.'"';
	$result = elasticsearch::elastic_search($type,null,0,$body); 
	return $result["hits"]["total"];

    }
}

/* Function to generate Graph Bar */
function generateDataGraphBar($query,$field,$sort,$sort_orientation,$facet_display_name,$size) {
    global $index;
    global $client;
    global $type;
    
    $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
    if (isset($sort)) {
        $query["aggs"]["counts"]["terms"]["order"][$sort] = $sort_orientation;
    }
    $query["aggs"]["counts"]["terms"]["size"] = $size;

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 10;
    $params["body"] = $query;

    $facet = $client->search($params);     
        
    $data_array= array();
    foreach ($facet['aggregations']['counts']['buckets'] as $facets) {
        array_push($data_array,'{"name":"'.$facets['key'].'","value":'.$facets['doc_count'].'}');
    };
    
    if ($field == "year" ) {
        $data_array_inverse = array_reverse($data_array);
        $comma_separated = implode(",", $data_array_inverse);
    } else {
        $comma_separated = implode(",", $data_array);
    }
    return $comma_separated;
};

class facebook {
    
    
    static function facebook_data($urls,$id) {
        global $fb;
//        $request[] = $fb->request(            
//                'GET',
//                '/',
//                array(
//                'id' => $url,
//                'fields' => 'og_object{likes.limit(0).summary(true),engagement,reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry),reactions.type(NONE).limit(0).summary(total_count).as(reactions_none),reactions.type(THANKFUL).limit(0).summary(total_count).as(reactions_thankful)},share{share_count,comment_count}'
//                )
//            );
        foreach ($urls as $url) {
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => $url
                    )
                );              
            }    
      
        
        $batch = [
            $request
        ];
        $responses = $fb->sendBatchRequest($batch);
        $graphObject = $responses->getGraphObject();
        $fb_share_count = 0;
        foreach ($responses as $key => $response) {
            $response_array = json_decode($response->getBody());
            echo '<a class="uk-button" href="'.(string)$response_array->{"id"}.'">Link</a>';
            if (isset($response_array->{"share"})) {
                 $fb_share_count+= $response_array->{'share'}->{'share_count'};
                 echo '<div class="uk-badge uk-badge-notification">'.(string)$response_array->{"share"}->{"share_count"}.' interações no facebook</div><br/>';
            } else {
                 $fb_share_count+= 0;
                 echo '<div class="uk-badge uk-badge-notification">Nenhuma interação no facebook</div><br/>';
            }

        }
        
         echo 'Total de interações no Facebook: '.$fb_share_count.'<br/>';
        
        $body["doc"]["facebook"]["facebook_total"] = $fb_share_count;
        $body["doc"]["facebook"]["date"] = date("Y-m-d");
        $body["doc_as_upsert"] = true;
        
        elasticsearch::elastic_update($id,"journals",$body);
    }
    
}

class altmetric_com {
    static function get_altmetrics ($doi) {
        
        $ch = curl_init();
        $method = "GET";
        $url = "https://api.altmetric.com/v1/doi/$doi";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $result = curl_exec($ch);
        var_dump($result);
        curl_close($ch);         
                
    }     
}


/*Deletar Excluídos*/
function exclude_deleted(){
    $ch = curl_init();
    $query = '
                {
                  "query": { 
                    "term": {
                      "status": "deleted"
                    }
                  }
                }    
    ';
    
    $method = "DELETE";
    $url = "http://$server/rppbci/journals/_query";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, 9200);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, TRUE);
    return $data["_indices"]["rppbci"]["deleted"];
    
}



?>
