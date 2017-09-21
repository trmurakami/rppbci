<?php 

include('inc/config.php');             
include('inc/functions.php');


if (isset($_GET["oai"])) {

    $oaiUrl = $_GET["oai"];
    $client_harvester = new \Phpoaipmh\Client(''.$oaiUrl.'');
    $myEndpoint = new \Phpoaipmh\Endpoint($client_harvester);


    // Result will be a SimpleXMLElement object
    $identify = $myEndpoint->identify();
    echo '<pre>';
 
    // Store repository data - Início

    $body_repository["doc"]["name"] = (string)$identify->Identify->repositoryName;
    $body_repository["doc"]["metadataFormat"] = $_GET["metadataFormat"];
    if (isset($_GET["qualis2015"])){
        $body_repository["doc"]["qualis2015"] = $_GET["qualis2015"];
    }    
    $body_repository["doc"]["date"] = (string)$identify->responseDate;
    $body_repository["doc"]["url"] = (string)$identify->request;
    $body_repository["doc_as_upsert"] = true;

    $insert_repository_result = elasticsearch::elastic_update($body_repository["doc"]["url"],"repository",$body_repository);
    print_r($insert_repository_result);

    // Store repository data - Fim

    // Results will be iterator of SimpleXMLElement objects
    $results = $myEndpoint->listMetadataFormats();
    $metadata_formats = [];
    foreach($results as $item) {
        $metadata_formats[] = $item->{"metadataPrefix"};
    }

    if ($_GET["metadataFormat"] == "nlm") {
        
        if (isset($_GET["set"])){
            $recs = $myEndpoint->listRecords('nlm',null,null,$_GET["set"]);
        } else {
            $recs = $myEndpoint->listRecords('nlm');
        }
        
        
        foreach($recs as $rec) {

            //print_r($rec);

            if ($rec->{'header'}->attributes()->{'status'} != "deleted"){

                $sha256 = hash('sha256', ''.$rec->{'header'}->{'identifier'}.'');


                $query["doc"]["source"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'journal-title'};
                $query["doc"]["harvester_id"] = (string)$rec->{'header'}->{'identifier'};
                if (isset($_GET["qualis2015"])) {
                    $query["doc"]["qualis2015"] = $_GET["qualis2015"];
                }                
                $query["doc"]["tipo"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-categories'}->{'subj-group'}->{'subject'};
                $query["doc"]["titulo"] = str_replace('"','',(string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'title-group'}->{'article-title'});
                $query["doc"]["ano"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'pub-date'}[0]->{'year'};
                $query["doc"]["doi"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-id'}[1];
                $query["doc"]["resumo"] = str_replace('"','',(string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'abstract'}->{'p'});

                // Palavras-chave
                if (isset($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'kwd-group'}[0]->{'kwd'})) {
                    foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'kwd-group'}[0]->{'kwd'} as $palavra_chave) {
                        $palavraschave_array = explode(".", (string)$palavra_chave);
                        foreach ($palavraschave_array  as $pc) {
                            $query["doc"]["palavras_chave"][] = trim($pc);
                        }

                    }
                }


                $i = 0;
                foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'contrib-group'}->{'contrib'} as $autores) {

                    if ($autores->attributes()->{'contrib-type'} == "author"){

                        $query["doc"]["autores"][$i]["nomeCompletoDoAutor"] = (string)$autores->{'name'}->{'given-names'}.' '.$autores->{'name'}->{'surname'};
                        $query["doc"]["autores"][$i]["nomeParaCitacao"] = (string)$autores->{'name'}->{'surname'}.', '.$autores->{'name'}->{'given-names'};

                        if(isset($autores->{'aff'})) {
                            $query["doc"]["autores"][$i]["afiliacao"] = (string)$autores->{'aff'};
                        }
                        if(isset($autores->{'uri'})) {
                            $query["doc"]["autores"][$i]["nroIdCnpq"] = (string)$autores->{'uri'};
                        }
                        $i++;
                    }
                }

                $query["doc"]["artigoPublicado"]["tituloDoPeriodicoOuRevista"] = str_replace('"','',(string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'journal-title'});
                $query["doc"]["artigoPublicado"]["nomeDaEditora"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'publisher'}->{'publisher-name'};
                $query["doc"]["artigoPublicado"]["issn"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'issn'};
                $query["doc"]["artigoPublicado"]["volume"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'volume'};
                $query["doc"]["artigoPublicado"]["fasciculo"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue'};
                $query["doc"]["artigoPublicado"]["paginaInicial"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-id'};
                $query["doc"]["artigoPublicado"]["serie"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-title'};
                $query["doc"]["url_principal"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'self-uri'}->attributes('http://www.w3.org/1999/xlink');

                $query["doc_as_upsert"] = true;


                foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'self-uri'} as $self_uri) {
                    $query["doc"]["relation"][]=(string)$self_uri->attributes('http://www.w3.org/1999/xlink');
                }

                //print_r($query);

                $resultado = elasticsearch::elastic_update($sha256,$type,$query);
                print_r($resultado);

                unset($query);
                flush();

            }
        }

    } elseif ($_GET["metadataFormat"] == "oai_dc") {

        if (isset($_GET["set"])){
            $recs = $myEndpoint->listRecords('oai_dc',null,null,$_GET["set"]);
        } else {
            $recs = $myEndpoint->listRecords('oai_dc');           
        }
        foreach($recs as $rec) {
            $data = $rec->metadata->children( 'http://www.openarchives.org/OAI/2.0/oai_dc/' );
            $rows = $data->children( 'http://purl.org/dc/elements/1.1/' );

            //var_dump ($rows);

            if (isset($rows->publisher)){                
                $body["doc"]["artigoPublicado"]["nomeDaEditora"] = (string)$rows->publisher;
            }

            if (isset($rows->title)){                
                $body["doc"]["titulo"] = (string)$rows->title[0];
            }

            if (isset($rows->relation)){                
                $body["doc"]["doi"] = (string)$rows->relation;
            }

            if (isset($rows->identifier)){                
                $body["doc"]["url_principal"] = (string)$rows->identifier;
            }

            if (isset($rows->identifier)){                
                $body["doc"]["relation"][] = (string)$rows->identifier;
            }            
            
            if (isset($rows->source)){                
                $body["doc"]["artigoPublicado"]["tituloDoPeriodicoOuRevista"] = (string)$rows->source;                
            }              
            
            
            if (isset($rows->creator)){ 
                $i = 0;
                foreach ($rows->creator as $author) {
                    $body["doc"]["autores"][$i]["nomeCompletoDoAutor"] = (string)$author;
                    $i++;
                }
            }

            if (isset($rows->date)){                
                $body["doc"]["ano"] = substr((string)$rows->date,0,4);
            }            

            $body["doc"]["relation"][] = "https://dx.doi.org/" . (string)$rows->relation;
            $id = (string)$rec->header->identifier;
            if (!empty((string)$rec->header->setSpec)) {
                $body["doc"]["source"] = (string)$rec->header->setSpec;
            } else {
                $body["doc"]["source"] = "";
            }
            
            $query["doc"]["artigoPublicado"]["issn"] = (string)$rec->header->setSpec;
                      
            $body["doc_as_upsert"] = true;
            unset($author);
            //print_r($body);
            $resultado = elasticsearch::elastic_update($id,$type,$body);
            //print_r($resultado);            
            //print_r($body);
            unset($body);    



        }
    

        
    } else {
        
        $recs = $myEndpoint->listRecords('rfc1807');
        var_dump($recs);
        foreach($recs as $rec) {
            if ($rec->{'header'}->attributes()->{'status'} != "deleted"){

                $sha256 = hash('sha256', ''.$rec->{'header'}->{'identifier'}.'');

                $query["doc"]["source"] = (string)$identify->Identify->repositoryName;
                    $query["doc"]["harvester_id"] = (string)$rec->{'header'}->{'identifier'};
                    if (isset($_GET["qualis2015"])) {
                        $query["doc"]["qualis2015"] = $_GET["qualis2015"];
                    }                   
                    $query["doc"]["tipo"] = (string)$rec->{'metadata'}->{'rfc1807'}->{'type'}[0];
                    $query["doc"]["titulo"] = str_replace('"','',(string)$rec->{'metadata'}->{'rfc1807'}->{'title'});
                    $query["doc"]["ano"] = substr((string)$rec->{'metadata'}->{'rfc1807'}->{'date'},0,4);
    //                $query["doc"]["doi"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-id'}[1];
                    $query["doc"]["resumo"] = str_replace('"','',(string)$rec->{'metadata'}->{'rfc1807'}->{'abstract'});
    //
                    // Palavras-chave
                    if (isset($rec->{'metadata'}->{'rfc1807'}->{'keyword'})) {
                        foreach ($rec->{'metadata'}->{'rfc1807'}->{'keyword'} as $palavra_chave) {
                            $pc_array = [];
                            $pc_array = explode(";", (string)$palavra_chave);
                            foreach ($pc_array as $pc_explode){
                                $pc_array_dot = explode("-", $pc_explode);
                            }
                            foreach ($pc_array_dot as $pc_dot){
                                $pc_array_end = explode(".", $pc_dot);
                            }                             
                            foreach ($pc_array_end as $pc) {
                                $query["doc"]["palavras_chave"][] = trim($pc);
                            }                             
                        }
                    }


                    $i = 0;
                    foreach ($rec->{'metadata'}->{'rfc1807'}->{'author'} as $autor) {
                        $autor_array = explode(";", (string)$autor);
                        $autor_nome_array = explode(",", (string)$autor_array[0]);

                            $query["doc"]["autores"][$i]["nomeCompletoDoAutor"] = $autor_nome_array[1].' '.ucwords(strtolower($autor_nome_array[0]));
                            $query["doc"]["autores"][$i]["nomeParaCitacao"] = (string)$autor_array[0];

                            if(isset($autor_array[1])) {
                                $query["doc"]["autores"][$i]["afiliacao"] = (string)$autor_array[1];
                            }
                            $i++;
                    }

                    $query["doc"]["artigoPublicado"]["tituloDoPeriodicoOuRevista"] = (string)$identify->Identify->repositoryName;
    //                $query["doc"]["artigoPublicado"]["nomeDaEditora"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'publisher'}->{'publisher-name'};
    //                $query["doc"]["artigoPublicado"]["issn"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'issn'};
    //                $query["doc"]["artigoPublicado"]["volume"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'volume'};
    //                $query["doc"]["artigoPublicado"]["fasciculo"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue'};
    //                $query["doc"]["artigoPublicado"]["paginaInicial"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-id'};
    //                $query["doc"]["artigoPublicado"]["serie"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-title'};
                    $query["doc"]["url_principal"] = (string)$rec->{'metadata'}->{'rfc1807'}->{'id'};


                    $query["doc"]["relation"][]=(string)$rec->{'metadata'}->{'rfc1807'}->{'id'};

                    $query["doc_as_upsert"] = true;

                    $resultado = elasticsearch::elastic_update($sha256,$type,$query);
                    print_r($resultado);

                    unset($query);
                    flush();


            }
        }        


    } 

} elseif (isset($_GET["delete"])) {
    echo $_GET["delete"];
    echo '<br/>';
    echo $_GET["delete_name"];

    $delete_repository = elasticsearch::elastic_delete($_GET["delete"],"repository");
    print_r($delete_repository);
    echo '<br/>';
    $body["query"]["query_string"]["query"] = 'source.keyword:"'.$_GET["delete_name"].'"';
    print_r($body);
    echo '<br/><br/>';
    $delete_records = elasticsearch::elastic_delete_by_query("journals",$body);
    print_r($delete_records);


} else {
    echo "URL não informada";
}
?>
