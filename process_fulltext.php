<!DOCTYPE html>
<?php

    include('inc/config.php');
    include('inc/functions.php');
    $query["query"]["query_string"]["query"] = "-_exists_:references";
    $query['sort']['ano.keyword']['order'] = 'desc';

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 1;
    $params["body"] = $query;

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"];

    echo 'Faltam: '.$total.'<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {
	    //print_r($r);
	    //echo '<br/><br/>';
   	    unset($r["_source"]["relation"][0]);
	    foreach ($r["_source"]["relation"] as $url_fulltext) {
		$url_fulltext = str_replace("view","download",$url_fulltext);
		print_r($url_fulltext);
		echo '<br/><br/>';

		$ch = curl_init();
		$source = $url_fulltext;
		curl_setopt($ch,CURLOPT_URL,$source);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);
		curl_close($ch);

		$destination = "tmp/00.pdf";
		$file = fopen($destination, "w+");
		fputs($file, $data);
		fclose($file);

		if (mime_content_type($destination) == "application/pdf"){

			$output = shell_exec('curl -v --form input=@./'.$destination.' localhost:8080/processReferences');
			$xml = simplexml_load_string($output);
			$json = json_encode($xml);
			$string = json_decode($json,true);
			//print_r($string);
			//echo "<br/><br/>";
			foreach ($string["text"]["back"]["div"]["listBibl"]["biblStruct"] as $ref){
				if (isset($ref["analytic"])){
					echo "analytic";
				} elseif (isset($ref["monogr"])) {
					echo "monogr";
					echo '<br/>';
					print_r($ref);
					echo '<br/><br/>';

					$update_ref["doc"]["references"]["citations"][]["name"] = $ref["monogr"]["title"];
					foreach ($ref["monogr"]["author"] as $ref_author) {
						if (isset($ref_author["persName"])) {
							$update_ref["doc"]["references"]["citations"][]["person"]["name"]["family"] = $ref_author["persName"]["surname"];
							if (isset($ref_author["persName"]["forename"])) {
								if (is_array($ref_author["persName"]["forename"])) {
									$update_ref["doc"]["references"]["citations"][]["person"]["name"]["given"] = implode(". ",$ref_author["persName"]["forename"]) . ".";
								} else {
									$update_ref["doc"]["references"]["citations"][]["person"]["name"]["given"] = $ref_author["persName"]["forename"] . ".";								
								}
							}
						} else {
							$update_ref["doc"]["references"]["citations"][]["person"]["name"]["family"] = $ref_author["surname"];
							if (isset($ref_author["forename"])) {
								if (is_array($ref_author["forename"])) {
									$update_ref["doc"]["references"]["citations"][]["person"]["name"]["given"] = implode(". ",$ref_author["forename"]) . ".";
								} else {
									$update_ref["doc"]["references"]["citations"][]["person"]["name"]["given"] = $ref_author["forename"] . ".";
								}
							}
						
						}
					}
					if (isset($ref["monogr"]["imprint"]["date"]["@attributes"]["when"])) {
						$update_ref["doc"]["references"]["citations"][]["datePublished"] = $ref["monogr"]["imprint"]["date"]["@attributes"]["when"];
					}
					if (isset($ref["monogr"]["imprint"]["pubPlace"])) {
						$update_ref["doc"]["references"]["citations"][]["publisher"]["organization"]["location"] = $ref["monogr"]["imprint"]["pubPlace"];
					}
					
					print_r($update_ref);
					echo '<br/><br/>';	
					unset($update_ref);				
				} else {
					echo "outro";
				}
				//$update_ref["doc"]["references"]["citations"][] = $ref;
				
				
			}
            //$update_ref["doc"]["references"]["date"] = date("Ymd");
            $update_ref["doc_as_upsert"] = true;
			print_r($update_ref);
			//$result_ref = elasticsearch::elastic_update($r['_id'],$type,$update_ref);
            //print_r($result_ref);
            unset($update_ref);

		}

	  }
    }
	    //echo '<br/><br/>';
	    //print_r($r["_source"]["relation"]);
	    //echo '<br/><br/>';

?>
