<!DOCTYPE html>
<?php
    include('inc/config.php'); 
    include('inc/functions.php');


if (empty($_GET)) {
$query='
{
    "fields" : ["_id","creator"],
    "query": {
        "filtered": {
            "query": {
                "match_all": {}
            },
            "filter": {
                "bool": {
                    "must": [
                    {
                        "missing" : { "field" : "tematres_ok" }
                    },
                    {
                        "exists":{
                            "field":"creator"
                        }
                    }]
                }
            }
        }
  },
  "size":5
}
';    
 
    
} else {
    
}

$cursor = query_elastic($query,$server);  

print_r($cursor);

?>