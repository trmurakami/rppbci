<!DOCTYPE html>
<?php

require 'inc/config.php';
require 'inc/functions.php';

if (!empty($_POST)) {
    Admin::addDivulgacao($_POST["titulo"], $_POST["url"], $_POST["id"]);
}

$result_get = Requests::getParser($_GET);
$limit = $result_get['limit'];
$page = $result_get['page'];
$params = [];
$params["index"] = $index;
$params["body"] = $result_get['query'];
$cursorTotal = $client->count($params);
$total = $cursorTotal["count"];
if (isset($_GET["sort"])) {
    $result_get['query']["sort"][$_GET["sort"]]["unmapped_type"] = "long";
    $result_get['query']["sort"][$_GET["sort"]]["missing"] = "_last";
    $result_get['query']["sort"][$_GET["sort"]]["order"] = "desc";
    $result_get['query']["sort"][$_GET["sort"]]["mode"] = "max";
} else {
    $result_get['query']["sort"]['facebook.facebook_total'] = "desc";
    $result_get['query']["sort"]['datePublished.keyword'] = "desc";
}
$params["body"] = $result_get['query'];
$params["size"] = $limit;
$params["from"] = $result_get['skip'];
$cursor = $client->search($params);



/* Citeproc-PHP*/
require 'inc/citeproc-php/CiteProc.php';
$csl_abnt = file_get_contents('inc/citeproc-php/style/abnt.csl');
$csl_apa = file_get_contents('inc/citeproc-php/style/apa.csl');
$csl_nlm = file_get_contents('inc/citeproc-php/style/nlm.csl');
$csl_vancouver = file_get_contents('inc/citeproc-php/style/vancouver.csl');
$lang = "br";
$citeproc_abnt = new citeproc($csl_abnt, $lang);
$mode = "reference";

?>
<html>
    <head>
        <?php require 'inc/meta-header.php' ?>
        <title>Resultado da busca</title>

        <!-- D3.js Libraries and CSS -->
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/d3/3.2.2/d3.v3.min.js"></script>

        <!-- UV Charts -->
        <script type="text/javascript" src=inc/uvcharts/uvcharts.full.min.js></script>

        <!-- Altmetric Script -->
        <script type='text/javascript' src='https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js'></script>

        <!-- PlumX Script -->
        <script type="text/javascript" src="//d39af2mgp1pqhg.cloudfront.net/widget-popup.js"></script>


    </head>
    <body>

        <?php
        if (file_exists("inc/analyticstracking.php")) {
            include_once "inc/analyticstracking.php";
        }
        ?>


        <!-- NAV -->
        <?php require 'inc/navbar.php'; ?>
        <!-- /NAV -->

        <br/><br/><br/><br/>

        <main role="main">
            <div class="container">

            <div class="row">
                <div class="col-8">                
                    <!-- PAGINATION -->
                    <?php UI::pagination($page, $total, $limit); ?>
                    <!-- /PAGINATION --> 
                    <br/>  

                    <!-- Resultados -->
                        <?php foreach ($cursor["hits"]["hits"] as $r) : ?>                       

                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['source'];?><?php echo " - v.".$r["_source"]["isPartOf"]["volume"]; ?><?php echo " - n.".$r["_source"]["isPartOf"]["issue"]; ?></h6>
                                <h5 class="card-title"><a class="text-dark" href="<?php echo $r['_source']['url']; ?>"><?php echo $r["_source"]['name']; ?> (<?php echo $r["_source"]['datePublished'];?>)</a></h5>

                                <?php if (!empty($r["_source"]["author"])) : ?>
                                    <?php 
                                        foreach ($r["_source"]["author"] as $autores) {
                                            if (!empty($autores["organization"]["name"])){
                                                $authors_array[]=''.$autores["person"]["name"].' ('.$autores["organization"]["name"].')';
                                            } else {
                                                $authors_array[]=''.$autores["person"]["name"].'';
                                            }
                                            
                                        }
                                        $array_aut = implode("; ",$authors_array);
                                        unset($authors_array);
                                        echo '<p class="text-muted"><b>Autores:</b> '.''. $array_aut.'</p>';
                                    ?>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['about'])) : ?>
                                    <?php 
                                        foreach ($r["_source"]['about'] as $assunto) {
                                            $assunto_array[]=''.$assunto.'';
                                        }
                                        $array_assunto = implode("; ",$assunto_array);
                                        unset($assunto_array);
                                        echo '<p class="text-muted"><b>Assuntos:</b> '.''. $array_assunto.'</p>';
                                    ?>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['description'])) : ?>
                                    <?php 
                                        echo '<p class="text-muted"><b>Resumo:</b> '.''. $r["_source"]['description'].'</p>';
                                    ?>
                                <?php endif; ?>                                
                                
                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                    <p>DOI: <a href="http://dx.doi.org/<?php echo $r["_source"]['doi'];?>" target="_blank"><?php echo $r["_source"]['doi'];?></a></p>
                                <?php endif; ?>
                                <?php print_r($r["_source"]); ?>

                                <?php if (!empty($r["_source"]['facebook']['facebook_total'])) : ?>
                                    
                                    <table class="table"><caption>Interações no Facebook</caption>        
                                        <thead>
                                            <tr>
                                                <th>Reactions</th>
                                                <th>Comentários</th>
                                                <th>Compartilhamentos</th>                        
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $r["_source"]['facebook']['reaction_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['comment_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['share_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['facebook_total'];?></td>
                                            </tr>
                                        </tbody>   
                                    </table><br/>
                                <?php endif; ?> 

                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                        <div data-badge-popover="right" data-badge-type="1" data-doi="<?php echo $r["_source"]['doi'];?>" data-hide-no-mentions="true" class="altmetric-embed"></div>
                                        <a href="https://plu.mx/plum/a/?doi=<?php echo $r["_source"]['doi'];?>" class="plumx-plum-print-popup" data-hide-when-empty="true" data-badge="true"></a>
                                        <div data-badge-details="right" data-badge-type="2" data-doi="<?php echo $r["_source"]['doi'];?>" data-condensed="true" data-hide-no-mentions="true" class="altmetric-embed"></div>
                                        <div><span class="__dimensions_badge_embed__" data-doi="<?php echo $r["_source"]['doi'];?>" data-hide-zero-citations="true" data-style="small_rectangle"></span></div></li>
                                <?php endif; ?>                                                                                                                           
                            
                            </div>
                        </div>


<!-- 

                        <div class="uk-grid-divider uk-padding-small" uk-grid>
                            <div class="uk-width-1-5@m">
                                <a href="result.php?search[]=source.keyword:&quot;< ?php echo $r["_source"]['source'];?>&quot;">< ?php echo $r["_source"]['source'];?></a>
                            </div>
                            <div class="uk-width-4-5@m">
                                <article class="uk-article">
                                <p class="uk-text-lead uk-margin-remove" style="font-size:115%"><a href="< ?php echo $r['_source']['url'];?>">< ?php echo $r["_source"]['name'];?>< ?php if (!empty($r["_source"]['datePublished'])) { echo ' ('.$r["_source"]['datePublished'].')'; } ?></a></p>

                                        <p class="uk-margin-remove">

                                        </p>


                                        < ?php if (isset($_GET["papel"])) : ?>
                                            < ?php if ($_GET["papel"] == "admin") : ?>
                                                <form class="uk-form uk-form-stacked" action="result.php?search[]=" method="POST">

                                                    <fieldset data-uk-margin>
                                                        <legend>Inserir URL de divulgação científica</legend>
                                                        <div class="uk-form-row">
                                                            <label class="uk-form-label" for="">Título</label>
                                                            <div class="uk-form-controls"><input type="text" placeholder="" name="titulo" class="uk-width-1-1"></div>
                                                        </div>
                                                        <div class="uk-form-row">
                                                            <label class="uk-form-label" for="">URL</label>
                                                            <div class="uk-form-controls"><input type="text" placeholder="" name="url" class="uk-width-1-1"></div>
                                                        </div>
                                                        <input type="hidden" name="id" value="< ?php echo $r['_id']; ?>">
                                                        <button class="uk-button">Enviar</button>
                                                    </fieldset>

                                                </form>
                                            < ?php endif; ?>
                                        < ?php endif; ?>
                                        
                                        <li class="uk-h6 uk-margin-top">
                                            <p>Métricas:</p>

                                            
                                            < !--
                                            <ul>
                                                <li>
                                                    < ?php Facebook::facebook_data($r["_source"]['relation'], $r["_id"]);?>
                                                    < ?php
                                                        if (!empty($r["_source"]['relation'])){
                                                            facebook::facebook_api_reactions($r["_source"]['relation'],$fb,$server,$r['_id']);
                                                            unset($facebook_url_array);
                                                        }
                                                    ?>
                                                    
                                                </li>
                                                <li>
                                                    < ?php if (isset($r["_source"]['div_cientifica'])) : ?>
                                                        < ?php foreach ($r["_source"]['div_cientifica'] as $div_source) :?>
                                                        < ?php $url_array[] = $div_source['url']; ?>
                                                        < ?php endforeach; ?>
                                                        < ?php Facebook::facebook_divulgacao($url_array, $r["_id"]);?>
                                                        < ?php unset($url_array);?>
                                                    < ?php endif; ?>
                                                </li>

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
-->
                    <?php endforeach; ?>

                    <!-- /RECORDS -->
                    <!-- PAGINATION -->
                    <?php UI::pagination($page, $total, $limit); ?>
                    <!-- /PAGINATION -->                                 
                
                </div>
                <div class="col-4">        

                        <!-- Facetas - Início -->
                        <h3 class="uk-panel-title">Refinar busca</h3>
                            <hr>
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">
                            <?php
                                $facets = new facets();
                                $facets->query = $result_get['query'];

                                if (!isset($_GET["search"])) {
                                    $_GET["search"] = null;
                                }

                                $facets->facet("source", 100, "Título do periódico", null, "_term", $_GET["search"]);
                                $facets->facet("datePublished", 120, "Ano de publicação", "desc", "_term", $_GET["search"]);
                                $facets->facet("author.person.name", 120, "Autores", null, "_term", $_GET["search"]);
                                $facets->facet("author.organization.name", 120, "Afiliação", null, "_term", $_GET["search"]);
                                //$facets->facet_range("numAutores", 100, "Número de autores - Range", $_GET["search"]);                                
                                $facets->facet("originalType", 10, "Seções", null, "_term", $_GET["search"]);                                
                                $facets->facet("about", 100, "Assuntos", null, "_term", $_GET["search"]);
                                $facets->facet("isPartOf.name", 100, "Editora", null, "_term", $_GET["search"]);
                                $facets->facet("isPartOf.volume", 100, "Volume", null, "_term", $_GET["search"]);
                                $facets->facet("isPartOf.issue", 100, "Fascículo", null, "_term", $_GET["search"]);
                                $facets->facet("isPartOf.issn", 100, "ISSN", null, "_term", $_GET["search"]);
                            ?>
                            </ul>
                            <hr>

                </div>
            </div>

            <div class="uk-width-3-4@s uk-width-4-6@m">








                </div>
            </div>
            <hr class="uk-grid-divider">


<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->            
        </div>



        <script>
        $('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){
            var url = window.location.href.split('&page')[0];
            window.location=url +'&page='+ (pageIndex+1);
        });
        </script>
        <script async src="https://badge.dimensions.ai/badge.js" charset="utf-8"></script>


    </body>
</html>

