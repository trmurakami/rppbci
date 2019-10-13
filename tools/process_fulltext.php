<!DOCTYPE html>
<?php

require 'inc/config.php';
require 'inc/functions.php';
$query["query"]["query_string"]["query"] = "-_exists_:references";
$query['sort']['datePublished.keyword']['order'] = 'desc';

$params = [];
$params["index"] = $index;
$params["type"] = $type;
$params["size"] = 2;
$params["body"] = $query;

$cursor = $client->search($params);
$total = $cursor["hits"]["total"];

echo 'Faltam: '.$total.'<br/><br/>';

foreach ($cursor["hits"]["hits"] as $r) {
    print_r($r);
    echo '<br/><br/>';
    unset($r["_source"]["relation"][0]);
    if (!empty($r["_source"]["relation"])) {

        foreach ($r["_source"]["relation"] as $url_fulltext) {
            $url_fulltext = str_replace("view", "download", $url_fulltext);
            print_r($url_fulltext);
            echo '<br/><br/>';

            $ch = curl_init();
            $source = $url_fulltext;
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);

            $destination = "tmp/00.pdf";
            $file = fopen($destination, "w+");
            fputs($file, $data);
            fclose($file);

            if (mime_content_type($destination) == "application/pdf") {

                $output = shell_exec('curl -v --form input=@./'.$destination.' '.$grobid_url.'/api/processReferences');
                $xml = simplexml_load_string($output);
                $json = json_encode($xml);
                $string = json_decode($json, true);
                print_r($string);
                if (count($string["text"]["back"]["div"]["listBibl"]["biblStruct"]) > 0) {
                    $i = 0;
                    foreach ($string["text"]["back"]["div"]["listBibl"]["biblStruct"] as $ref) {

                        $i_author = 0;
                        if (isset($ref["analytic"])) {

                            $update_ref["doc"]["references"]["citations"][$i]["analytic"]["name"] = $ref["monogr"]["title"];
                            $update_ref["doc"]["references"]["citations"][$i]["name"] = $ref["analytic"]["title"];
                            if (isset($ref["monogr"]["author"])) {
                                foreach ($ref["monogr"]["author"] as $ref_author) {
                                    if (isset($ref_author["persName"])) {
                                        $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["persName"]["surname"];
                                        if (isset($ref_author["persName"]["forename"])) {
                                            if (is_array($ref_author["persName"]["forename"])) {
                                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["persName"]["forename"]) . ".";
                                            } else {
                                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["persName"]["forename"] . ".";
                                            }
                                        }
                                    } else {
                                        $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["surname"];
                                        if (isset($ref_author["forename"])) {
                                            if (is_array($ref_author["forename"])) {
                                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["forename"]) . ".";
                                            } else {
                                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["forename"] . ".";
                                            }
                                        }
                                    }
                                    $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["citation"] = $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] . ", " . $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"];
                                    $i_author++;
                                }
                            }
                            if (isset($ref["monogr"]["imprint"]["date"]["@attributes"]["when"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["datePublished"] = substr($ref["monogr"]["imprint"]["date"]["@attributes"]["when"], 0, 4);
                            }
                            if (isset($ref["monogr"]["imprint"]["pubPlace"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["publisher"]["organization"]["location"] = $ref["monogr"]["imprint"]["pubPlace"];
                            }
                            if (isset($ref["monogr"]["imprint"]["publisher"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["publisher"]["organization"]["name"] = $ref["monogr"]["imprint"]["publisher"];
                            }
                            if (isset($ref["monogr"]["idno"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["doi"] = $ref["monogr"]["idno"];
                            }
                            if (isset($ref["monogr"]["ptr"]["@attributes"]["target"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["url"] = $ref["monogr"]["ptr"]["@attributes"]["target"];
                            }

                            foreach ($ref["analytic"]["author"] as $ref_author) {
                                if (isset($ref_author["persName"])) {
                                    $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["persName"]["surname"];
                                    if (isset($ref_author["persName"]["forename"])) {
                                        if (is_array($ref_author["persName"]["forename"])) {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["persName"]["forename"]) . ".";
                                        } else {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["persName"]["forename"] . ".";
                                        }
                                    }
                                } else {
                                    $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["surname"];
                                    if (isset($ref_author["forename"])) {
                                        if (is_array($ref_author["forename"])) {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["forename"]) . ".";
                                        } else {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["forename"] . ".";
                                        }
                                    }

                                }
                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["citation"] = $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] . ", " . $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"];
                                $i_author++;
                            }
                            if (isset($ref["analytic"]["idno"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["doi"] = $ref["analytic"]["idno"];
                            }

                        } elseif (isset($ref["monogr"])) {
                            $update_ref["doc"]["references"]["citations"][$i]["name"] = $ref["monogr"]["title"];
                            foreach ($ref["monogr"]["author"] as $ref_author) {
                                if (isset($ref_author["persName"])) {
                                    $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["persName"]["surname"];
                                    if (isset($ref_author["persName"]["forename"])) {
                                        if (is_array($ref_author["persName"]["forename"])) {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["persName"]["forename"]) . ".";
                                        } else {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["persName"]["forename"] . ".";
                                        }
                                    }
                                } else {
                                    $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] = $ref_author["surname"];
                                    if (isset($ref_author["forename"])) {
                                        if (is_array($ref_author["forename"])) {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = implode(". ", $ref_author["forename"]) . ".";
                                        } else {
                                            $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"] = $ref_author["forename"] . ".";
                                        }
                                    }

                                }
                                $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["citation"] = $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["family"] . ", " . $update_ref["doc"]["references"]["citations"][$i]["author"][$i_author]["person"]["name"]["given"];
                                $i_author++;
                            }
                            if (isset($ref["monogr"]["imprint"]["date"]["@attributes"]["when"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["datePublished"] = substr($ref["monogr"]["imprint"]["date"]["@attributes"]["when"], 0, 4);
                            }
                            if (isset($ref["monogr"]["imprint"]["pubPlace"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["publisher"]["organization"]["location"] = $ref["monogr"]["imprint"]["pubPlace"];
                            }
                            if (isset($ref["monogr"]["imprint"]["publisher"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["publisher"]["organization"]["name"] = $ref["monogr"]["imprint"]["publisher"];
                            }
                            if (isset($ref["monogr"]["idno"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["doi"] = $ref["monogr"]["idno"];
                            }
                            if (isset($ref["monogr"]["ptr"]["@attributes"]["target"])) {
                                $update_ref["doc"]["references"]["citations"][$i]["url"] = $ref["monogr"]["ptr"]["@attributes"]["target"];
                            }


                        } else {
                            echo "outro";
                        }
                        $i++;
                    }

                }
                $update_ref["doc"]["references"]["data"] = date("Ymd");
                $update_ref["doc_as_upsert"] = true;
                print_r($update_ref);
                $result_ref = elasticsearch::elastic_update($r['_id'], $type, $update_ref);
                print_r($result_ref);
                unset($update_ref);

            } else {
                $update_ref["doc"]["references"]["data"] = date("Ymd");
                $update_ref["doc_as_upsert"] = true;
                $result_ref = elasticsearch::elastic_update($r['_id'], $type, $update_ref);
                print_r($result_ref);
                unset($update_ref);
            }

    }

    } else {
        $update_ref["doc"]["references"]["data"] = date("Ymd");
        $update_ref["doc_as_upsert"] = true;
        $result_ref = elasticsearch::elastic_update($r['_id'], $type, $update_ref);
        print_r($result_ref);
        unset($update_ref);
    }
}
?>
