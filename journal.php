<?php
/**
 * Journal page
 */

require 'inc/config.php';
require 'inc/functions.php';


/* QUERY */

if (isset($_GET["journal"])) {
    $query["query"]["bool"]["filter"][0]["term"]["source.keyword"] = urlencode($_GET["journal"]);
} else {
    echo 'Não foi definido um periódico';
}

$query["aggs"]["counts"]["terms"]["field"] = "datePublished.keyword";
$query["aggs"]["counts"]["terms"]["order"]["_term"] = "desc";
$query["aggs"]["counts"]["terms"]["size"] = 100;

$response = Elasticsearch::search(null, 0, $query, $alternative_index);  


function queryElasticSubfield($subfield, $filter0, $filter1 = null) {

    $query["query"]["bool"]["filter"][0]["term"][$filter0[0].".keyword"] = $filter0[1];
    $query["aggs"]["counts"]["terms"]["field"] = "$subfield.keyword";
    $query["aggs"]["counts"]["terms"]["order"]["_term"] = "desc";
    $query["aggs"]["counts"]["terms"]["size"] = 100;   

    $response = Elasticsearch::search(null, 0, $query, null);
    return $response;

}

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>

    <?php
    require 'inc/meta-header.php';
    ?>   
    <title><?php echo $branch_abrev; ?> - Detalhe do periódico: <?php echo $_GET["journal"];?></title>

    <!-- Altmetric Script -->
    <script type='text/javascript' src='https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js'></script>

    <!-- PlumX Script -->
    <script type="text/javascript" src="//cdn.plu.mx/widget-details.js"></script>

</head>
<body>
    <?php
    if (file_exists("inc/analyticstracking.php")) {
        include_once "inc/analyticstracking.php";
    }
    ?>
    <!-- TOP -->
    <div class="top-wrap uk-position-relative uk-background-secondary">
        <div class="uk-section uk-section-default" style="padding:0">

            <!-- NAV -->
            <?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->
        
            <main role="main">
                <div class="container">
                    <div class="row">
                        <div class="col-8">                       

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h3 class="card-subtitle mb-2 text-muted"><?php echo $_GET["journal"];?></h3>
                                    <div class="row">

                                    <?php 
                                        foreach ($response["aggregations"]["counts"]["buckets"] as $datePublished) {
                                            //echo "<br/><br/>";                                            
                                            //print_r($datePublished);
                                            echo '
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                        <h5 class="card-title">'.$datePublished["key"].' ('.$datePublished["doc_count"].')</h5>';   
                                                        $filter0[0] = "datePublished";
                                                        $filter0[1] = $datePublished["key"];                                                      
                                                        $volumes = queryElasticSubfield("isPartOf.volume", $filter0);
                                                        print_r($volumes);
                                                        foreach ($volumes["aggregations"]["counts"]["buckets"] as $volume) {
                                                            print_r($volume);
                                                        }
                                            
                                            echo '      </div>
                                                    </div>
                                                </div>                                         
                                            
                                            ';
                                        }
                                    
                                    ?>   

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mt-3">  

                    </div>
                </div>
                <hr class="uk-grid-divider">
            <?php require 'inc/footer.php'; ?>                   
            
            </div>
    </body>
</html>