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
if (!empty($_SESSION['oauthuserdata'])) {
    $query["aggs"]["counts"]["terms"]["missing"] = "Não preenchido";
}
$query["aggs"]["counts"]["terms"]["order"]["_term"] = "desc";
$query["aggs"]["counts"]["terms"]["size"] = 100;

$response = Elasticsearch::search(null, 0, $query, $alternative_index);

//echo "<br/><br/><br/><br/><br/>";
//print_r($response);


$result_count = count($response["aggregations"]["counts"]["buckets"]);    

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
                                                        <h5 class="card-title">'.$datePublished["key"].' ('.$datePublished["doc_count"].')</h5>
                                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                                        </div>
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