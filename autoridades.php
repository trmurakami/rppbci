<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');            
            include('inc/meta-header.php');

            /* Consulta n registros ainda não corrigidos */
            if (empty($_GET)) {
                $body["query"]["query_string"]["query"] = "+_exists_:autores -_exists_:aff_ok";
            } 

            $params = [];
            $params["index"] = $index;
            $params["type"] = $type;
            $params["_source"] = ["_id","autores"];
            $params["size"] = 20;        
            $params["body"] = $body;   

            $response = $client->search($params);
                
            echo 'Total de registros faltantes: '.$response['hits']['total'].'';
        
        ?> 
        <title>Autoridades - RPPBCI</title>
    </head>
    <body> 
        
        <div class="uk-container uk-container-center uk-margin-large-bottom">
            <?php include('inc/navbar.php'); ?>
            
                <?php 
            
                    // Pega cada um dos registros da resposta
                    foreach ($response["hits"]["hits"] as $registro) {                        
                        
                        $i = 0;                        
                        // Para cada autor no registro
                        foreach ($registro['_source']['autores'] as $autor) {
                                echo '<br/>';
                                print_r($autor);
                            
                                if (isset($autor["afiliacao"])) {
                                    $ch = curl_init();
                                    $method = "GET";
                                    $url = 'http://bdpife2.sibi.usp.br/instituicoes/vocab/services.php?task=fetch&arg='.rawurlencode($autor["afiliacao"]).'&output=json';                            
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                                    $result_get_id_tematres = curl_exec($ch);
                                    $resultado_get_id_tematres = json_decode($result_get_id_tematres, true);
                                    curl_close($ch);
                                } else {
                                    $resultado_get_id_tematres["resume"]["cant_result"] = 0;
                                }
                            
 
                            
                                if ($resultado_get_id_tematres["resume"]["cant_result"] != 0) {

                                    foreach($resultado_get_id_tematres["result"] as $key => $val) {
                                        $term_key = $key;
                                    }
                                    
                                    $ch = curl_init();
                                    $method = "GET";
                                    $url = 'http://bdpife2.sibi.usp.br/instituicoes/vocab/services.php?task=fetchTerm&arg='.$term_key.'&output=json';
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                                    $result_term = curl_exec($ch);
                                    $resultado_term = json_decode($result_term, true);
                                    $termo_correto = $resultado_term["result"]["term"]["string"];
                                    curl_close($ch);
                                    
                                    if(!empty($autor["nomeCompletoDoAutor"])){
                                        $body_upsert["doc"]["autores"][$i]["nomeCompletoDoAutor"] = $autor["nomeCompletoDoAutor"];
                                    }
                                    if(!empty($autor["nomeParaCitacao"])){
                                        $body_upsert["doc"]["autores"][$i]["nomeParaCitacao"] = $autor["nomeParaCitacao"];
                                    }
                                    if(!empty($autor["nroIdCnpq"])){
                                        $body_upsert["doc"]["autores"][$i]["nroIdCnpq"] = $autor["nroIdCnpq"];
                                    }
                                    $body_upsert["doc"]["autores"][$i]["afiliacao"] = $termo_correto;
                                    
                                    


                                } else {
                                    
                                    //echo "Não obteve resultados no tematres<br/>";

                                    if(!empty($autor["nomeCompletoDoAutor"])){
                                        $body_upsert["doc"]["autores"][$i]["nomeCompletoDoAutor"] = $autor["nomeCompletoDoAutor"];
                                    }
                                    if(!empty($autor["nomeParaCitacao"])){
                                        $body_upsert["doc"]["autores"][$i]["nomeParaCitacao"] = $autor["nomeParaCitacao"];
                                    }
                                    if(!empty($autor["nroIdCnpq"])){
                                        $body_upsert["doc"]["autores"][$i]["nroIdCnpq"] = $autor["nroIdCnpq"];
                                    }
                                    if(!empty($autor["afiliacao"])){
                                        $body_upsert["doc"]["autores"][$i]["afiliacao"] = $autor["afiliacao"];
                                    }

                                } 
                            $i++;
                            
                        }
                            $body_upsert["doc"]["aff_ok"] = true;
                            $body_upsert["doc_as_upsert"] = true;
                            echo '<br/>';
                            //print_r($body_upsert);
                            $resultado_upsert = elasticsearch::elastic_update($registro["_id"],$type,$body_upsert); 
                            print_r($resultado_upsert);
                            unset($body_upsert);
                                                   
                        echo "<br/>=========================================================<br/><br/>";
                    } 
            
                ?> 
   
        </div>
    </body>
</html>