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
                        {"facebook.total" : {"order" : "desc"}}
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
            echo '<a class="ui small header" href="'.$r['_source']['url_principal'].'"><h4 class="uk-comment-title">'.$r['_source']['title'][0].' ('.$r['_source']['year'][0].' - '.$r['_source']['journalci_title'][0].') - <b>'.$r['_source']['facebook']['total'].' interações</b></h4></a>';
            echo '<div class="extra">';
            if (!empty($r["_source"]['creator'])) {
                echo '<div class="uk-comment-meta";">';    
                foreach ($r["_source"]['creator'] as $autores) {
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




function gera_consulta_citacao($citacao) {
    $type = get_type($citacao["tipo"]);
    $author_array = array();
    foreach ($citacao["creator"] as $autor_citation){
        $array_authors = explode(',', $autor_citation);
        $author_array[] = '{"family":"'.$array_authors[0].'","given":"'.$array_authors[1].'"}';
    };
    $authors = implode(",",$author_array);
    if (!empty($citacao["ispartof"])) {
        $container = '"container-title": "'.$citacao["ispartof"].'",';
    } else {
        $container = "";
    };
    if (!empty($citacao["doi"])) {
        $doi = '"DOI": "'.$citacao["doi"].'",';
    } else {
        $doi = "";
    };
    if (!empty($citacao["url_principal"])) {
        $url = '"URL": "'.$citacao["url_principal"].'",';
    } else {
        $url = "";
    };
    if (!empty($citacao["publisher"])) {
        $publisher = '"publisher": "'.$citacao["publisher"].'",';
    } else {
        $publisher = "";
    };
    if (!empty($citacao["publisher_place"])) {
        $publisher_place = '"publisher-place": "'.$citacao["publisher_place"].'",';
    } else {
        $publisher_place = "";
    };
    $volume = "";
    $issue = "";
    $page_ispartof = "";
    if (!empty($citacao["ispartof_data"])) {
        foreach ($citacao["ispartof_data"] as $ispartof_data) {
            if (strpos($ispartof_data, 'v.') !== false) {
                $volume = '"volume": "'.str_replace("v.","",$ispartof_data).'",';
            } elseif (strpos($ispartof_data, 'n.') !== false) {
                $issue = '"issue": "'.str_replace("n.","",$ispartof_data).'",';
            } elseif (strpos($ispartof_data, 'p.') !== false) {
                $page_ispartof = '"page": "'.str_replace("p.","",$ispartof_data).'",';
            }
        }
    }
    $data = json_decode('{
    "title": "'.$citacao["title"][0].'",
    "type": "'.$type.'",
    '.$container.'
    '.$doi.'
    '.$url.'
    '.$publisher.'
    '.$publisher_place.'
    '.$volume.'
    '.$issue.'
    '.$page_ispartof.'
    "issued": {
    "date-parts": [
    [
    "'.$citacao["year"].'"
    ]
    ]
    },
    "author": [
    '.$authors.'
    ]
    }');
    
    return $data;    
    
}

/* Pegar o tipo de material */
function get_type($material_type){
  switch ($material_type) {
  case "ARTIGO DE JORNAL":
      return "article-newspaper";
      break;
  case "Artigo de periódico":
      return "article-journal";
      break;
  case "PARTE DE MONOGRAFIA/LIVRO":
      return "chapter";
      break;
  case "APRESENTACAO SONORA/CENICA/ENTREVISTA":
      return "interview";
      break;
  case "TRABALHO DE EVENTO-RESUMO":
      return "paper-conference";
      break;
  case "TRABALHO DE EVENTO":
      return "paper-conference";
      break;     
  case "TESE":
      return "thesis";
      break;          
  case "TEXTO NA WEB":
      return "post-weblog";
      break;
  default:
      return "article-journal";
      break;          
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

/*Facebook Altmetrics*/

function facebook_altmetrics_update($server,$facebook_id,$facebook_array){    
    $ch = curl_init();
    $method = "POST";
    $facebook_id_corrigido = urlencode($facebook_id);
    $url = "http://$server/rppbci/journals/$facebook_id_corrigido/_update";
	$query = 
	     '{
		"doc":{
		    "facebook" : {
			'.implode(",",$facebook_array).'
		    },
		    "date":"'.date("Y-m-d").'"
		},                    
		"doc_as_upsert" : true
	    }'; 
    //print_r($query);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, 9200);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $result = curl_exec($ch);
    //var_dump($result);
    curl_close($ch); 
}

function facebook_altmetrics($server,$url_array,$facebook_token,$facebook_id) {
    
    foreach ($url_array as $url) {
        
        $query_facebook = 'https://graph.facebook.com/v2.7?fields=og_object{reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry)},share&ids='.$url.'&access_token='.$facebook_token.'';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query_facebook);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $altmetrics = json_decode($output, true);
    
        if (!empty($altmetrics[''.$url.'']['og_object'])) {
            //print_r($altmetrics[''.$url.'']);
            
            $like_total+= $altmetrics[''.$url.'']['og_object']['reactions_like']['summary']['total_count'];
            $love_total+= $altmetrics[''.$url.'']['og_object']['reactions_love']['summary']['total_count'];
            $wow_total+= $altmetrics[''.$url.'']['og_object']['reactions_wow']['summary']['total_count'];
            $haha_total+= $altmetrics[''.$url.'']['og_object']['reactions_haha']['summary']['total_count'];
            $sad_total+= $altmetrics[''.$url.'']['og_object']['reactions_sad']['summary']['total_count'];
            $angry_total+= $altmetrics[''.$url.'']['og_object']['reactions_angry']['summary']['total_count'];
            $share_total+= $altmetrics[''.$url.'']['share']['share_count'];
            $comment_total+= $altmetrics[''.$url.'']['share']['comment_count'];

        
        } else {
            
            $like_total+= 0;
            $love_total+= 0;
            $wow_total+= 0;
            $haha_total+= 0;
            $sad_total+= 0;
            $angry_total+= 0;
            $share_total+= 0;
            $comment_total+= 0;
            
        }
        
        unset($ch);
        unset($query_facebook);
        unset($altmetrics);
        unset($output);
    }
        
    $facebook_array[] = '"reactions_like":'.$like_total.'';
    $facebook_array[] = '"reactions_love":'.$love_total.'';
    $facebook_array[] = '"reactions_wow":'.$wow_total.'';
    $facebook_array[] = '"reactions_haha":'.$haha_total.'';
    $facebook_array[] = '"reactions_sad":'.$sad_total.'';
    $facebook_array[] = '"reactions_angry":'.$angry_total.'';
    $facebook_array[] = '"share_count":'.$share_total.'';
    $facebook_array[] = '"comment_count":'.$comment_total.'';

    $altmetrics_total+= $like_total;
    $altmetrics_total+= $love_total;
    $altmetrics_total+= $wow_total;
    $altmetrics_total+= $haha_total;
    $altmetrics_total+= $sad_total;
    $altmetrics_total+= $angry_total;
    $altmetrics_total+= $share_total;
    $altmetrics_total+= $comment_total;


    $facebook_array[] = '"total":'.$altmetrics_total.'';
    
    facebook_altmetrics_update($server,$facebook_id,$facebook_array);

    echo '<p>Facebook: <br/>';
    echo 'Likes: '.$like_total.'<br/>';
    echo 'Love: '.$love_total.'<br/>';
    echo 'Wow: '.$wow_total.'<br/>';
    echo 'Haha: '.$haha_total.'<br/>';
    echo 'Sad: '.$sad_total.'<br/>';
    echo 'Angry: '.$angry_total.'<br/>';
    echo 'Compartilhamentos: '.$share_total.'<br/>';
    echo 'Comentários: '.$comment_total.'<br/>';
    echo '</p>';
    
    unset($ch);
    unset($altmetrics);
    unset($output);
    unset($like_total);
    unset($love_total);
    unset($wow_total);
    unset($haha_total);
    unset($sad_total);
    unset($angry_total);
    unset($share_total);
    unset($comment_total);
    unset($altmetrics_total);
    unset($facebook_array);
}

function facebook_api_reactions($url_array,$fb,$server,$facebook_id) {
    
    foreach ($url_array as $url){
        $string = '?id='.$url.'';
        $request[] = $fb->request('GET',$string);
    }
    
    $batch = [
        $request
    ];

    $responses = $fb->sendBatchRequest($batch);

    $fb_share_count = "";
    $fb_comment_count = "";
    $fb_reactions_count = "";
    $altmetrics_total = "";
    
    foreach ($responses as $key => $response) {
      if ($response->isError()) {
        //$e = $response->getThrownException();
        //echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
        //echo '<p>Graph Said: ' . "\n\n";
        //var_dump($e->getResponse());
      } else {
          
          $response_json = $response->getBody();
          $response_json_decode = json_decode($response_json);
          
          if (!empty($response_json_decode->{'og_object'})){
              $fb_og_id[] = $response_json_decode->{'og_object'}->{'id'};
              $fb_share_count+= $response_json_decode->{'share'}->{'share_count'};
              $fb_comment_count+= $response_json_decode->{'share'}->{'comment_count'};            
             
          } else {
              $fb_share_count+= 0;
              $fb_comment_count+= 0;
              $fb_reactions_count+= 0;
          }
          
                
        //echo "<p>(" . $key . ") HTTP status code: " . $response->getHttpStatusCode() . "<br />\n";
        //echo "Response: " . $response->getBody() . "</p>\n\n";
        //echo "<hr />\n\n";
      }
    }
    
    if (!empty($fb_og_id)){
        
        
        foreach ($fb_og_id as $fid){
            
            $response = $fb->get('/'.$fid.'/reactions?summary=true');
            $response_reaction_decode = json_decode($response->getBody());
            //print_r($response_reaction_decode);
            $fb_reactions_count+= $response_reaction_decode->{'summary'}->{'total_count'};
            
            
        }
        
        
    }
    
    $facebook_array[] = '"share_count":'.$fb_share_count.'';
    $facebook_array[] = '"comment_count":'.$fb_comment_count.'';
    $facebook_array[] = '"reactions_count":'.$fb_reactions_count.'';
    
    $altmetrics_total+= $fb_share_count;
    $altmetrics_total+= $fb_comment_count;
    $altmetrics_total+= $fb_reactions_count;
    
    $facebook_array[] = '"total":'.$altmetrics_total.'';
    $facebook_array[] = '"date_altmetrics":"'.date("Y-m-d").'"';
    
    facebook_altmetrics_update($server,$facebook_id,$facebook_array);
    
    echo "<hr />\n\n";
    echo "Facebook:<br/>";
    echo '    Reactions: '.$fb_reactions_count.'<br/>';
    echo '    Compartilhamentos: '.$fb_share_count.'<br/>';
    echo '    Comentários: '.$fb_comment_count.'';
    echo "<hr />\n\n";
    
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