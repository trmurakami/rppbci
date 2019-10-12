if ($use_grobid == true) {
                    foreach ($query["doc"]["relation"] as $url_download) {
                        $content = file_get_contents(str_replace("view", "download", $url_download));
                        $citation_xml = grobidQuery($content, $grobid_url);
                        if (!empty($citation_xml)) {
                            $i_references = 0;
                            foreach ($citation_xml->text->back->div->listBibl->biblStruct as $reference) {
                                $reference_json =  json_encode($reference, JSON_UNESCAPED_UNICODE);
                                $reference_array = json_decode($reference_json, true);
                                if (!empty($reference_array["monogr"]["title"])) {
                                    $query["doc"]["references"][$i_references]["monogrTitle"] = $reference_array["monogr"]["title"];
                                }
                                if (isset($reference_array["monogr"]["idno"])) {
                                    $query["doc"]["references"][$i_references]["doi"] = $reference_array["monogr"]["idno"];
                                }
                                if (isset($reference_array["monogr"]["meeting"])) {
                                    print_r($reference_array["monogr"]["meeting"]);
                                    if (is_array($reference_array["monogr"]["meeting"])) {
                                        $query["doc"]["references"][$i_references]["pubPlace"] = $reference_array["monogr"]["meeting"]["address"]["addrLine"];
                                    } else {
                                        $query["doc"]["references"][$i_references]["meeting"] = $reference_array["monogr"]["meeting"];
                                    }

                                }
                                if (isset($reference_array["monogr"]["author"])) {
                                    foreach ($reference_array["monogr"]["author"] as $ref_author) {
                                        if (isset($ref_author["persName"])) {
                                            if (is_array($ref_author["persName"]["forename"])) {
                                                $query["doc"]["references"][$i_references]["authors"][] = $ref_author["persName"]["surname"] .', ' . implode(" ", $ref_author["persName"]["forename"]);
                                            } else {
                                                $query["doc"]["references"][$i_references]["authors"][] = $ref_author["persName"]["surname"] . ', ' . $ref_author["persName"]["forename"];
                                            }
                                        }
                                    }
                                }
                                if (isset($reference_array["analytic"])) {
                                    $query["doc"]["references"][$i_references]["analyticTitle"] = $reference_array["analytic"]["title"];
                                    if (isset($reference_array["analytic"]["idno"])) {
                                        $query["doc"]["references"][$i_references]["doi"] = $reference_array["analytic"]["idno"];
                                    }
                                    if (isset($reference_array["analytic"]["ptr"]["@attributes"]["target"])) {
                                        $query["doc"]["references"][$i_references]["link"] = $reference_array["analytic"]["ptr"]["@attributes"]["target"];
                                    }
                                }
                                if (isset($reference_array["monogr"]["imprint"])) {
                                    $query["doc"]["references"][$i_references]["datePublished"] = $reference_array["monogr"]["imprint"]["date"]["@attributes"]["when"];
                                    if (isset($reference_array["monogr"]["imprint"]["publisher"])) {
                                        $query["doc"]["references"][$i_references]["publisher"] = $reference_array["monogr"]["imprint"]["publisher"];
                                    }
                                    if (isset($reference_array["monogr"]["imprint"]["pubPlace"])) {
                                        $query["doc"]["references"][$i_references]["pubPlace"] = $reference_array["monogr"]["imprint"]["pubPlace"];
                                    }
                                }
                                //print_r($reference_array);

                                /* Verifica se a referencia existe e cria um registro de citação em caso negativo */

                                if (isset($reference_array["analytic"])) {
                                    queryRef($reference_array["analytic"]["title"]);
                                } elseif (!empty($reference_array["monogr"]["title"])) {
                                    queryRef($reference_array["monogr"]["title"]);
                                }

                                /* FIM */

                                $i_references++;
                            }
                        }
                        unset($content);
                        unset($citation_array);
                    }
                }