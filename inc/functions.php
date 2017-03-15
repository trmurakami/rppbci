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
    
    /*Facebook Altmetrics*/
    function facebook_altmetrics_update($server,$facebook_id,$facebook_array){    
        $ch = curl_init();
        $method = "POST";
        $sha256 = hash('sha256', ''.$facebook_id.'');    
        $url = "http://$server/rppbci/journals/$sha256/_update";
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
        //print_r($graphObject);
        /* handle the result */
        $fb_share_count = 0;
        foreach ($responses as $key => $response) {
            $response_array = json_decode($response->getBody());
        //print_r($response_array);
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
    

    static function facebook_api_reactions($url_array,$fb,$server,$facebook_id) {

        foreach ($url_array as $url){
            $string = '?id='.$url.'';
            $request[] = $fb->request('GET',$string);
        }

        $batch = [
            $request
        ];

        $responses = $fb->sendBatchRequest($batch);
        
        print_r($responses);

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

        //facebook_altmetrics_update($server,$facebook_id,$facebook_array);

        echo "<hr />\n\n";
        echo "Facebook:<br/>";
        echo '    Reactions: '.$fb_reactions_count.'<br/>';
        echo '    Compartilhamentos: '.$fb_share_count.'<br/>';
        echo '    Comentários: '.$fb_comment_count.'';
        echo "<hr />\n\n";

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