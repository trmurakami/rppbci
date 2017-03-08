<?php 

include('inc/config.php');             
include('inc/functions.php');

//$oaiUrl   = 'http://openscholarship.wustl.edu/do/oai/';
$oaiUrl   = 'http://www.seer.ufal.br/index.php/cir/oai';
$client_harvester = new \Phpoaipmh\Client(''.$oaiUrl.'');
$myEndpoint = new \Phpoaipmh\Endpoint($client_harvester);


// Result will be a SimpleXMLElement object
$identify = $myEndpoint->identify();
echo '<pre>';
print_r($identify);

// Results will be iterator of SimpleXMLElement objects
$results = $myEndpoint->listMetadataFormats();
$metadata_formats = [];
foreach($results as $item) {
    $metadata_formats[] = $item->{"metadataPrefix"};
}

if (in_array("nlm", $metadata_formats)) { 
    echo "Tem nlm";
    $recs = $myEndpoint->listRecords('nlm');
    foreach($recs as $rec) {
        if ($rec->{'header'}->attributes()->{'status'} != "deleted"){
            print_r($rec->{'metadata'});
        }
    }    
} elseif (in_array("oai_dc", $metadata_formats))  {
    echo "Tem oai_dc";
    $recs = $myEndpoint->listRecords('oai_dc');    
    foreach($recs as $rec) {
        if ($rec->{'header'}->attributes()->{'status'} != "deleted"){
            print_r($rec->{'metadata'});
        }
    }
    
} else {
    echo "Este repositório não possui um formato compatível";
}


$recs = $myEndpoint->listRecords('oai_dc');
foreach($recs as $rec) {
    if ($rec->{"header"}->attributes()->{"status"} != "deleted") {
        var_dump($rec);
    }
    
}


if ($_GET["metadata_format"] == "nlm") {

    // Recs will be an iterator of SimpleXMLElement objects
    $recs = $myEndpoint->listRecords('nlm');

    // The iterator will continue retrieving items across multiple HTTP requests.
    // You can keep running this loop through the *entire* collection you
    // are harvesting.  All OAI-PMH and HTTP pagination logic is hidden neatly
    // behind the iterator API.
    foreach($recs as $rec) {

        if ($rec->{'header'}->attributes()->{'status'} != "deleted"){
        
            $sha256 = hash('sha256', ''.$rec->{'header'}->{'identifier'}.'');

            
            $query["doc"]["source"] = $rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'journal-title'};
            $query["doc"]["harvester_id"] = $rec->{'header'}->{'identifier'};
            $query["doc"]["tag"] = $_GET["tag"];
            $query["doc"]["tipo"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-categories'}->{'subj-group'}->{'subject'};
            $query["doc"]["titulo"] = str_replace('"','',$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'title-group'}->{'article-title'});
            $query["doc"]["ano"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'pub-date'}[0]->{'year'};
            $query["doc"]["doi"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-id'}[1];
            $query["doc"]["resumo"] = str_replace('"','',$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'abstract'}->{'p'});
            
            // Palavras-chave 
            foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'kwd-group'}[0]->{'kwd'} as $palavra_chave) {
                $query["doc"]["palavras_chave"][] = $palavra_chave;        
            }
            
            $i = 0
            foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'contrib-group'}->{'contrib'}  as $autores) {

                if ($autores->attributes()->{'contrib-type'} == "author"){

                    $query["doc"]["autores"][$i]["nomeCompletoDoAutor"] = $autores->{'name'}->{'given-names'}.' '.$autores->{'name'}->{'surname'};
                    $query["doc"]["autores"][$i]["nomeParaCitacao"] = $autores->{'name'}->{'surname'}.', '.$autores->{'name'}->{'given-names'};

                    if(isset($autores->{'aff'})) {
                        $query["doc"]["autores"][$i]["afiliacao"] = $autores->{'aff'};
                    }              
                    if(isset($autores->{'uri'})) {
                        $query["doc"]["autores"][$i]["nroIdCnpq"] = $autores->{'uri'};
                    }  
                    $i++;
                }
            }             
            
            $query["doc"]["artigoPublicado"]["tituloDoPeriodicoOuRevista"] = str_replace('"','',$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'journal-title'});
            $query["doc"]["artigoPublicado"]["nomeDaEditora"] = $rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'publisher'}->{'publisher-name'};
            $query["doc"]["artigoPublicado"]["issn"] = $rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'issn'};
            $query["doc"]["artigoPublicado"]["volume"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'volume'};
            $query["doc"]["artigoPublicado"]["fasciculo"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue'};
            $query["doc"]["artigoPublicado"]["paginaInicial"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-id'};
            $query["doc"]["artigoPublicado"]["serie"] = $rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-title'};
            
            
            $query["doc"]["doc_as_upsert"] = true;

            $resultado = store_record($sha256,"trabalhos",$query);
            print_r($resultado);

        }
    }
} else {
    $recs = $myEndpoint->listRecords('oai_dc');
    foreach($recs as $rec) {
        print_r($rec);
    }
    
}


?>