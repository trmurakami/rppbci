<?php

if (file_exists('uspfind_core/uspfind_core.php')) {
    include 'uspfind_core/uspfind_core.php';
} else {
    include '../uspfind_core/uspfind_core.php';
}


class ElasticsearchInstall {

    /**
     * Cria o indice
     *
     * @param string   $indexName  Nome do indice
     *
     */
    static function createIndex($indexName, $client)
    {
        $createIndexParams = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                    'analysis' => [
                        'filter' => [
                            'portuguese_stop' => [
                                'type' => 'stop',
                                'stopwords' => 'portuguese'
                            ],
                            'my_ascii_folding' => [
                                'type' => 'asciifolding',
                                'preserve_original' => true
                            ],
                            'portuguese_stemmer' => [
                                'type' => 'stemmer',
                                'language' =>  'light_portuguese'
                            ]
                        ],
                        'analyzer' => [
                            'portuguese' => [
                                'tokenizer' => 'standard',
                                'filter' =>  [ 
                                    'lowercase', 
                                    'my_ascii_folding',
                                    'portuguese_stop',
                                    'portuguese_stemmer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $responseCreateIndex = $client->indices()->create($createIndexParams);
    }
    
  
    /**
     * Cria o mapeamento
     *
     * @param string   $indexName  Nome do indice
     *
     */
    static function mappingsIndex($indexName, $client, $mappings = null)
    {
        if (isset($mappings)) {
            $mappingsParams = $mappings;
        } else {
            $mappingsParams = [
                'index' => $indexName,
                'body' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'portuguese',
                            'fields' => [
                                'keyword' => [
                                    'type' => 'keyword',
                                    'ignore_above' => 256
                                ]
                            ]
                        ], 
                        'datePublished' => [
                            'type' => 'integer'
                        ]                                         
                    ]
                ]
            ];
        }
        // Update the index mapping
        $client->indices()->putMapping($mappingsParams);
    }      
}

/**
 * Class Inicio
 *
 * @category Início
 * @package  
 * @author   Tiago Murakami 
 * @license  
 * @link     
 *
 */
class Inicio
{
    
    static function contar_registros($server) 
    {
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
    
    static function top_registros() 
    {
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

        foreach ($data["hits"]["hits"] as $r) {
            //var_dump($r);

            echo '<dl class="uk-description-list">'; 
            //print_r($r);
            echo '<dt><a href="'.$r['_source']['url'].'">'.$r['_source']['titulo'].' ('.$r['_source']['datePublished'].' - '.$r['_source']['source'].') - <b>'.$r['_source']['facebook']['facebook_total'].' interações</b></a></dt>';
            echo '<dd>';
            if (!empty($r["_source"]['autores'])) {                  
                foreach ($r["_source"]['autores'] as $autores) {
                    echo '<a href="result.php?search[]=creator.keyword:&quot;'.$autores["nomeCompletoDoAutor"].'&quot;">'.$autores["nomeCompletoDoAutor"].'</a>, ';
                }
            }
            echo '</dd>';
            echo '</dl>';
        }     
    }    
    
    /**
     * Facetas - Página inicial
     *
     * @param Field $field Campo
     */
    static function facetasInicio($field) 
    {
        global $index;
        global $type;
        global $client;

        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        $query["aggs"]["counts"]["terms"]["order"]["_term"] = "asc";
        $query["aggs"]["counts"]["terms"]["size"] = 1000;

        $params = [];
        $params["index"] = $index;
        $params["type"] = $type;
        $params["size"] = 10;
        $params["body"] = $query;           

        $data = $client->search($params);


        foreach ($data["aggregations"]["counts"]["buckets"] as $facets) {
            echo '<div class="uk-width-medium-1-5"><div class="uk-panel uk-panel-hover" data-my-category="'.$facets['key'][0].'" data-my-category2="'.$facets['doc_count'].'"><p><i class="uk-icon-bookmark"></i> <a href="result.php?filter[]='.$field.':&quot;'.htmlentities(urlencode($facets['key'])).'&quot;">'.$facets['key'].' ('.number_format($facets['doc_count'],0,',','.').')</a></p></div></div>';
        }

    } 

    /*Filtro - Página inicial*/
    static function facetas_filter($field) {
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
            echo '<option value="source.keyword:&quot;'.$facets['key'].'&quot;" style="color:#333">'.$facets['key'].'</option>';
        }

    }     
     
    
}

/**
 * Class Admin
 *
 * @category Admin
 * @package  
 * @author   Tiago Murakami 
 * @license  
 * @link     
 *
 */
class Admin
{
    
    /** 
     * Facetas - Página inicial
     * 
     * @param Field $field Campo
     */
    static function sources($field) 
    {
        global $index;
        global $type;
        global $client;

        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        $query["aggs"]["counts"]["terms"]["order"]["_term"] = "asc";
        $query["aggs"]["counts"]["terms"]["size"] = 10000;

        $query["sort"]["name.keyword"] = "asc";

        $query["query"]["bool"]["must"]["query_string"]["query"] = "type:journal";

        $params = [];
        $params["index"] = $index;
        $params["type"] = $type;
        $params["size"] = 1000;
        $params["body"] = $query;           

        $data = $client->search($params);

        //print_r($data);

        echo '<table class="uk-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Periódico</th>';
        echo '<th>Formato</th>';
        echo '<th>Qualis2015</th>';
        echo '<th>Data da coleta</th>';
        echo '<th>Núm. de registros</th>';
        echo '<th>Atualizar tudo</th>';
        echo '<th>Excluir</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($data["hits"]["hits"] as $repository) {
            echo '<tr><td><a href="'.$repository['_id'].'">'.$repository['_source']['name'].'</a></td><td>'.$repository['_source']['metadataFormat'].'</td>';
            if (!empty($repository['_source']['qualis2015'])){
                echo '<td>'.$repository['_source']['qualis2015'].'</td>';
            } else {
                echo '<td>Sem informação</td>';
            }
            echo  '<td>'.$repository['_source']['date'].'</td><td>';
	        echo Admin::countRecords($repository['_source']['name']);
	        echo '</td><td><a class="uk-button uk-button-success" href="harvester.php?oai='.$repository['_source']['url'].'&qualis2015='.$repository['_source']['qualis2015'].'&metadataFormat='.$repository['_source']['metadataFormat'].'">Update</a></td>';
            echo '<td><a class="uk-button uk-button-danger" href="harvester.php?delete='.$repository['_id'].'&delete_name='.htmlentities(urlencode($repository['_source']['name'])).'">Excluir</a></td></tr>';
            
	}
        echo '</tbody>';
        echo '</table>';

    }

    /** 
     * Count records
     * 
     * @param Name $name Name
     */    
    static function countRecords($name) 
    {
        global $type;
        $body["query"]["query_string"]["query"] = 'source.keyword:"'.$name.'"';
        $result = elasticsearch::elastic_search($type, null, 0, $body); 
        return $result["hits"]["total"];
    }

    /** 
     * Adicionar divulgação científica
     * 
     * @param Titulo $titulo Título
     * @param URL $url URL
     */      
    static function addDivulgacao($titulo,$url,$id)
    {

        $result_get = elasticsearch::elastic_get($id, "journals", "div_cientifica");
        if (count($result_get['_source']['div_cientifica']) > 0) {
            $body["doc"]["div_cientifica"] = $result_get['_source']['div_cientifica'];
        }
        $array = [];        
        $array["titulo"] = $titulo;
        $array["url"] = $url;                
        $body["doc"]["div_cientifica"][] = $array;
        $body["doc_as_upsert"] = true;
        
        $result_insert = elasticsearch::elastic_update($id,"journals",$body);    
        print_r($result_insert);    

    }
}

class ProcessaResultados
{
    
    /* Function to generate Graph Bar */
    static function generateDataGraphBar($query, $field, $sort, $sort_orientation, $facet_display_name, $size)
    {
        global $index;
        global $client;
        global $type;
        
        $query["aggs"]["counts"]["terms"]["field"] = "$field.keyword";
        if (isset($sort)) {
            $query["aggs"]["counts"]["terms"]["order"][$sort] = $sort_orientation;
        }
        $query["aggs"]["counts"]["terms"]["size"] = $size;
        
        $params = [
            'index' => $index,
            'type' => $type,
            'size'=> 0, 
            'body' => $query
        ]; 
        $facet = $client->search($params);  
        $data_array= array();
        foreach ($facet['aggregations']['counts']['buckets'] as $facets) {
            array_push($data_array,'{"name":"'.$facets['key'].'","value":'.$facets['doc_count'].'}');
        };
        if ($field == "datePublished" ) {
            $data_array_inverse = array_reverse($data_array);
            $comma_separated = implode(",", $data_array_inverse);
        } else {
            $comma_separated = implode(",", $data_array);
        }
        return $comma_separated;
    }
}    

/**
 * Class Facebook
 *
 * @category External sources
 * @package  
 * @author   Tiago Murakami 
 * @license  
 * @link     
 *
 */
class Facebook 
{   
    
    static function facebook_data($urls, $id) 
    {
        global $fb;
        foreach ($urls as $url) {
            $url_limpa = str_replace("http://", "", $url);
            $url_limpa = str_replace("https://", "", $url_limpa);
            
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => "http://".$url_limpa,
                    'fields' => 'engagement'
                    )
                );             
            
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => "https://".$url_limpa,
                    'fields' => 'engagement'    
                    )
                );              
            }    
      
        
        $batch = [
            $request
        ];
        //$responses = $fb->sendBatchRequest($batch);
        try {
            $responses = $fb->sendBatchRequest($batch);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }        
        $graphObject = $responses->getGraphObject();        
        $fb_reaction_count = 0;
        $fb_comment_count = 0;
        $fb_share_count = 0;
        $fb_comment_plugin_count = 0;
        $fb_total = 0;
        $i = 0;
        foreach ($responses as $key => $response) {
            $response_array = json_decode($response->getBody());
            //echo '<a class="uk-button" href="'.(string)$response_array->{"id"}.'">Link</a>';
            if (isset($response_array->{"engagement"})) {
                
                $fb_reaction_count+= $response_array->{"engagement"}->{'reaction_count'};
                $fb_comment_count+= $response_array->{"engagement"}->{'comment_count'};
                $fb_share_count+= $response_array->{"engagement"}->{'share_count'};
                $fb_comment_plugin_count+= $response_array->{"engagement"}->{'comment_plugin_count'};
                $fb_total_link= $response_array->{"engagement"}->{'reaction_count'} + $response_array->{"engagement"}->{'comment_count'} + $response_array->{"engagement"}->{'share_count'} + $response_array->{"engagement"}->{'comment_plugin_count'};                
                //echo '<div class="uk-badge uk-badge-notification">'.$fb_total_link.' interações no facebook</div><br/>';
                $fb_total+= $fb_total_link;
                ${"fb" . $i} = $fb_total_link;
                
            } else {
                $fb_reaction_count+= 0;
                $fb_comment_count+= 0;
                $fb_share_count+= 0;
                $fb_comment_plugin_count+= 0;
                $fb_total+= 0;
                ${"fb" . $i} = 0;
                //echo '<div class="uk-badge uk-badge-notification">Nenhuma interação no facebook</div><br/>';
            }
            $i++;

        }
        
    
            // echo '<table class="uk-table"><caption>Interações no Facebook</caption>';        
            // echo '<thead>
            //         <tr>
            //             <th>Reactions</th>
            //             <th>Comentários</th>
            //             <th>Compartilhamentos</th>                        
            //             <th>Total</th>
            //         </tr>
            //     </thead>';
            // echo '<tbody>
            //         <tr>
            //             <td>'.$fb_reaction_count.'</td>
            //             <td>'.$fb_comment_count.'</td>
            //             <td>'.$fb_share_count.'</td>
            //             <td>'.$fb_total.'</td>
            //         </tr>
            //       </tbody>';   
            // echo '</table><br/>';
        
        $f = 0;
        while ($f < $i):
            $body["doc"]["facebook"]["fb".$f] = ${"fb" . $f};
            $f++;
        endwhile;
        $body["doc"]["facebook"]["reaction_count"] = $fb_reaction_count;
        $body["doc"]["facebook"]["comment_count"] = $fb_comment_count;
        $body["doc"]["facebook"]["share_count"] = $fb_share_count;
        $body["doc"]["facebook"]["comment_plugin_count"] = $fb_comment_plugin_count;        
        $body["doc"]["facebook"]["facebook_total"] = $fb_total;
        $body["doc"]["facebook"]["date"] = date("Y-m-d");
        $body["doc_as_upsert"] = true;
        
        Elasticsearch::update($id, $body);
    }

    static function facebook_doi($urls,$id) 
    {
        global $fb;
        foreach ($urls as $url) {
            $url_limpa = str_replace("http://dx.doi.org/", "", $url);
            $url_limpa = str_replace("https://dx.doi.org/", "", $url_limpa);
            
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => "https://dx.doi.org/".$url_limpa,
                    'fields' => 'engagement'    
                    )
                );              
            }    
      
        
        $batch = [
            $request
        ];
        $responses = $fb->sendBatchRequest($batch);
        $graphObject = $responses->getGraphObject();        
        $fb_reaction_count = 0;
        $fb_comment_count = 0;
        $fb_share_count = 0;
        $fb_comment_plugin_count = 0;
        $fb_total = 0;
        $i = 0;
        foreach ($responses as $key => $response) {
            $response_array = json_decode($response->getBody());
            //echo '<a class="uk-button" href="'.(string)$response_array->{"id"}.'">Link</a>';
            if (isset($response_array->{"engagement"})) {
                
                $fb_reaction_count+= $response_array->{"engagement"}->{'reaction_count'};
                $fb_comment_count+= $response_array->{"engagement"}->{'comment_count'};
                $fb_share_count+= $response_array->{"engagement"}->{'share_count'};
                $fb_comment_plugin_count+= $response_array->{"engagement"}->{'comment_plugin_count'};
                $fb_total_link= $response_array->{"engagement"}->{'reaction_count'} + $response_array->{"engagement"}->{'comment_count'} + $response_array->{"engagement"}->{'share_count'} + $response_array->{"engagement"}->{'comment_plugin_count'};                
                //echo '<div class="uk-badge uk-badge-notification">'.$fb_total_link.' interações no facebook</div><br/>';
                $fb_total+= $fb_total_link;
                ${"fb" . $i} = $fb_total_link;
                
            } else {
                $fb_reaction_count+= 0;
                $fb_comment_count+= 0;
                $fb_share_count+= 0;
                $fb_comment_plugin_count+= 0;
                $fb_total+= 0;
                ${"fb" . $i} = 0;
                //echo '<div class="uk-badge uk-badge-notification">Nenhuma interação no facebook pelo DOI</div><br/>';
            }
            $i++;

        }
        
    
            // echo '<table class="uk-table"><caption>Interações no Facebook pelo DOI</caption>';        
            // echo '<thead>
            //         <tr>
            //             <th>Reactions</th>
            //             <th>Comentários</th>
            //             <th>Compartilhamentos</th>                        
            //             <th>Total</th>
            //         </tr>
            //     </thead>';
            // echo '<tbody>
            //         <tr>
            //             <td>'.$fb_reaction_count.'</td>
            //             <td>'.$fb_comment_count.'</td>
            //             <td>'.$fb_share_count.'</td>
            //             <td>'.$fb_total.'</td>
            //         </tr>
            //       </tbody>';   
            // echo '</table><br/>';
        
        $f = 0;
        while ($f < $i):
            $body["doc"]["facebook_doi"]["fb".$f] = ${"fb" . $f};
            $f++;
        endwhile;
        $body["doc"]["facebook_doi"]["reaction_count"] = $fb_reaction_count;
        $body["doc"]["facebook_doi"]["comment_count"] = $fb_comment_count;
        $body["doc"]["facebook_doi"]["share_count"] = $fb_share_count;
        $body["doc"]["facebook_doi"]["comment_plugin_count"] = $fb_comment_plugin_count;        
        $body["doc"]["facebook_doi"]["facebook_total"] = $fb_total;
        $body["doc"]["facebook_doi"]["date"] = date("Y-m-d");
        $body["doc_as_upsert"] = true;
        
        elasticsearch::elastic_update($id,"journals",$body);
    }

    static function facebook_divulgacao($urls,$id) 
    {
        global $fb;
        foreach ($urls as $url) {
            $url_limpa = str_replace("http://", "", $url);
            $url_limpa = str_replace("https://", "", $url_limpa);
            
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => "http://".$url_limpa,
                    'fields' => 'engagement,og_object'
                    )
                );             
            
            $request[] = $fb->request(            
                    'GET',
                    '/',
                    array(
                    'id' => "https://".$url_limpa,
                    'fields' => 'engagement,og_object'    
                    )
                );              
            }    
      
        
        $batch = [
            $request
        ];
        $responses = $fb->sendBatchRequest($batch);
        $graphObject = $responses->getGraphObject();        
        $fb_reaction_count = 0;
        $fb_comment_count = 0;
        $fb_share_count = 0;
        $fb_comment_plugin_count = 0;
        $fb_total = 0;
        $i = 0;
        foreach ($responses as $key => $response) {
            $response_array = json_decode($response->getBody());
            if  (!empty($response_array->{"og_object"}->{'title'})){
                $title = (string)$response_array->{"og_object"}->{'title'};
            } else {
                $title = "Link";
            }
            echo '<a class="uk-button" href="'.(string)$response_array->{"id"}.'">'.$title.'</a>';
            if (isset($response_array->{"engagement"})) {
                
                $fb_reaction_count+= $response_array->{"engagement"}->{'reaction_count'};
                $fb_comment_count+= $response_array->{"engagement"}->{'comment_count'};
                $fb_share_count+= $response_array->{"engagement"}->{'share_count'};
                $fb_comment_plugin_count+= $response_array->{"engagement"}->{'comment_plugin_count'};
                $fb_total_link= $response_array->{"engagement"}->{'reaction_count'} + $response_array->{"engagement"}->{'comment_count'} + $response_array->{"engagement"}->{'share_count'} + $response_array->{"engagement"}->{'comment_plugin_count'};                
                echo '<div class="uk-badge uk-badge-notification">'.$fb_total_link.'</div><br/>';
                $fb_total+= $fb_total_link;
                ${"fb" . $i} = $fb_total_link;
                
            } else {
                $fb_reaction_count+= 0;
                $fb_comment_count+= 0;
                $fb_share_count+= 0;
                $fb_comment_plugin_count+= 0;
                $fb_total+= 0;
                ${"fb" . $i} = 0;
                echo '<div class="uk-badge uk-badge-notification">Nenhuma interação no facebook em divulgação cientifica</div><br/>';
            }
            $i++;

        }
        
    
            echo '<table class="uk-table"><caption>Interações no Facebook em divulgação cientifica</caption>';        
            echo '<thead>
                    <tr>
                        <th>Reactions</th>
                        <th>Comentários</th>
                        <th>Compartilhamentos</th>                        
                        <th>Total</th>
                    </tr>
                </thead>';
            echo '<tbody>
                    <tr>
                        <td>'.$fb_reaction_count.'</td>
                        <td>'.$fb_comment_count.'</td>
                        <td>'.$fb_share_count.'</td>
                        <td>'.$fb_total.'</td>
                    </tr>
                  </tbody>';   
            echo '</table><br/>';
        
        $f = 0;
        while ($f < $i):
            $body["doc"]["facebook_divulgacao"]["fb".$f] = ${"fb" . $f};
            $f++;
        endwhile;
        $body["doc"]["facebook_divulgacao"]["reaction_count"] = $fb_reaction_count;
        $body["doc"]["facebook_divulgacao"]["comment_count"] = $fb_comment_count;
        $body["doc"]["facebook_divulgacao"]["share_count"] = $fb_share_count;
        $body["doc"]["facebook_divulgacao"]["comment_plugin_count"] = $fb_comment_plugin_count;        
        $body["doc"]["facebook_divulgacao"]["facebook_total"] = $fb_total;
        $body["doc"]["facebook_divulgacao"]["date"] = date("Y-m-d");
        $body["doc_as_upsert"] = true;
        
        elasticsearch::elastic_update($id,"journals",$body);
    }        
    
}

class altmetric_com
{
    static function get_altmetrics ($doi,$id) 
    {
        
        $ch = curl_init();
        $method = "GET";
        $url = "https://api.altmetric.com/v1/doi/$doi";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $result = curl_exec($ch);
        //print_r(json_decode($result,true));
        curl_close($ch);
        
        
        $body["doc"]["altmetric_com"] = json_decode($result,true);
        $body["doc"]["altmetric_com"]["date"] = date("Y-m-d");
        $body["doc_as_upsert"] = true;
        
        $result = elasticsearch::elastic_update($id,"journals",$body);        
        //print_r($result);        
    }     
}

function grobidQuery($content, $grobid_url) 
{

    // initialise the curl request
    $request = curl_init('143.107.154.38:8070/api/processReferences');
    // send a file
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt(
        $request,
        CURLOPT_POSTFIELDS,
        array('input' => $content)
    );
    // output the response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    //echo curl_exec($request);
    $result = curl_exec($request);
    if (!empty($result)) {
        $xml = simplexml_load_string($result);
    } else {
        $xml = "";
    } 
    //foreach ($xml->text->back->div->listBibl->biblStruct as $citation) {
    //    $citation_array[] =  json_encode($citation, JSON_UNESCAPED_UNICODE);
    //}
    return $xml;         
    curl_close($request);

}


/*Deletar Excluídos*/
function exclude_deleted()
{
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

class USP 
{

    static function query_bdpi($query_title,$query_year,$sha256) { 
        global $type; 
        
        $query_title =  str_replace('"','',$query_title);
        $query = '
        {
            "min_score": 35,
            "query":{
                "bool": {
                    "should": [	
                        {
                            "multi_match" : {
                                "query":      "'.$query_title.'",
                                "type":       "cross_fields",
                                "fields":     [ "name" ],
                                "minimum_should_match": "85%" 
                            }
                        },	    
                        {
                            "multi_match" : {
                                "query":      "'.$query_year.'",
                                "type":       "best_fields",
                                "fields":     [ "datePublished" ],
                                "operator":   "and",
                                "minimum_should_match": "75%" 
                            }
                        }
                    ],
                    "minimum_should_match" : 2               
                }
            }
        }
        ';

        $ch = curl_init();
        $method = "POST";
        $url = "http://172.31.0.90/sibi/producao/_search";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);

        if ($data["hits"]["total"] > 0){
            echo '<div class="uk-alert">';
            echo '<h3>Registro na Biblioteca Digital de Produção Intelectual da USP</h3>';
            foreach ($data["hits"]["hits"] as $match){
                echo '<p>Nota de proximidade: '.$match["_score"].' - <a href="http://bdpi.usp.br/single.php?_id='.$match["_id"].'">'.$match["_source"]["type"].' - '.$match["_source"]["name"].' ('.$match["_source"]["datePublished"].')</a><br/> Autores: ';   
                foreach ($match["_source"]['author'] as $autores) {
                    echo ''.$autores['person']['name'].', ';
                }
                if (isset($match["_source"]["doi"])){
                    $doc["doc"]["bdpi"]["doi_bdpi"] = $match["_source"]["doi"];
                } else {
                    
                }
                if (!isset($match["_source"]["USP"]["views_counter"])){
                    $match["_source"]["USP"]["views_counter"] = 0;
                }
                echo '<br/>Quantidade de visualizações de registro na BDPI USP: '.$match["_source"]["USP"]["views_counter"].'';
                $doc["doc"]["bdpi"]["views_counter"] = $match["_source"]["USP"]["views_counter"];
                echo '</p>';
            }
            echo '</div>';            

            $doc["doc"]["bdpi"]["existe"] = "Sim";
            $doc["doc_as_upsert"] = true;
            //print_r($doc);
            $result_elastic = elasticsearch::elastic_update($sha256,$type,$doc);
        }
        return $data;
    }

    /*
    * Consulta o Qualis de uma Obra *
    */
    static function qualis_issn ($issn) {

        $query["query"]["ids"]["values"][] = $issn;

        $ch = curl_init();
        $method = "POST";
        $url = "http://172.31.0.90/serial_metrics/qualis/_search";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);   
        return $data;
    }
    
    /*
    * Consulta o JCR de uma Obra *
    */
    static function jcr_issn ($issn) {

        $query["query"]["ids"]["values"][] = $issn;

        $ch = curl_init();
        $method = "POST";
        $url = "http://172.31.0.90/serial_jcr/JCR/_search";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);   
        return $data;
    }
     
    /*
    * Consulta indexação na Web of Science de uma Obra *
    */
    static function wos_issn ($issn) {

        $query["query"]["ids"]["values"][] = $issn;
        
        $ch = curl_init();
        $method = "POST";
        $url = "http://172.31.0.90/serial_web_of_science/WOS/_search";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);   
        return $data;

    }    

    /*
    * Consulta indexação na Web of Science de uma Obra *
    */
    static function citescore_issn ($issn) {

        $query["query"]["ids"]["values"][] = $issn;
        
        $ch = curl_init();
        $method = "POST";
        $url = "http://172.31.0.90/citescore/issn/_search";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);   
        return $data;
        
    }        


}

function dimensionsAPI($doi)
{
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://metrics-api.dimensions.ai/doi/'.$doi.'',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
    )
    );
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    $data = json_decode($resp, true);
    return $data;
    // Close request to clear up some resources
    curl_close($curl);    
}   

class Homepage
{
    /**
     * Function last records
     *
     * @return array Last records
     */
    static function getLastRecords()
    {

        global $client;
        global $index;
        $params = [];
        $params["index"] = $index;
        $params["size"] = 0;
        $query["query"]["bool"]["must"]["query_string"]["query"] = "*";
        $query["sort"]["_uid"]["unmapped_type"] = "long";
        $query["sort"]["_uid"]["missing"] = "_last";
        $query["sort"]["_uid"]["order"] = "desc";
        $query["sort"]["_uid"]["mode"] = "max";         
        $params["body"] = $query; 
        $response = Elasticsearch::search(null, 10, $query);

        foreach ($response["hits"]["hits"] as $r) {

            echo '
            
            <div class="card bg-light mb-3">
            <div class="card-header">'.$r["_source"]['source'].' | '.$r["_source"]['type'].'</div>
            <div class="card-body">
                <div class="row no-gutters">
                <div class="col-md-1">
                </div>
                <div class="col-md-11">
                    <div class="card-body">';

                    if (!empty($r["_source"]['name'])) {
                        echo '<h5 class="card-title"><a href="item/'.$r['_id'].'">'.$r["_source"]['name'].'';
                        if (!empty($r["_source"]['datePublished'])) {
                            echo ' ('.$r["_source"]['datePublished'].')';
                        }
                        echo '</a></h5>';
                    };

                    if (!empty($r["_source"]['author'])) {
                        foreach ($r["_source"]['author'] as $autores) {
                            if (!empty($autores["person"]["orcid"])) {
                                $orcidLink = '<a href="'.$autores["person"]["orcid"].'"><img src="https://orcid.org/sites/default/files/images/orcid_16x16.png"></a>';
                            } else {
                                $orcidLink = '';
                            }
                            $autArray[] = '<a href="result.php?filter[]=author.person.name:&quot;'.$autores["person"]["name"].'&quot;">'.$autores["person"]["name"].'</a> '.$orcidLink.'';
                            unset($orcidLink);
                        }
                        echo '<p class="card-text"><small class="text-muted">'.implode(" | ", $autArray).'</small></p>';
                        unset($autArray);
                    };

                    echo '
                    </div></div>
                </div>
                </div>
            </div>            
            ';
        }

    }
    
    static function fieldAgg($field)
    {
        global $type;
        $query = '{
            "aggs": {
                "group_by_state": {
                    "terms": {
                        "field": "'.$field.'.keyword",
                        "size" : 5
                    }
                }
            }
        }';
        $response = Elasticsearch::search(null, 0, $query);
        foreach ($response["aggregations"]["group_by_state"]["buckets"] as $facets) {
            echo '<li class="list-group-item"><a href="result.php?filter[]='.$field.':&quot;'.$facets['key'].'&quot;">'.$facets['key'].' ('.number_format($facets['doc_count'], 0, ',', '.').')</a></li>';
        }
    }    
}

?>
