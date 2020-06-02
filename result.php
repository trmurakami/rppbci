<!DOCTYPE html>
<?php
session_start();

$errorMsg = "";
$validUser = $_SESSION["login"] === true;
if(isset($_POST["username"])) {
  $validUser = $_POST["username"] == "rppbci_admin" && $_POST["password"] == "rppbci_admin";
  if(!$validUser) $errorMsg = "Usuário ou senha inválidos.";
    else $_SESSION["login"] = true;
}


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
// require 'inc/citeproc-php/CiteProc.php';
// $csl_abnt = file_get_contents('inc/citeproc-php/style/abnt.csl');
// $csl_apa = file_get_contents('inc/citeproc-php/style/apa.csl');
// $csl_nlm = file_get_contents('inc/citeproc-php/style/nlm.csl');
// $csl_vancouver = file_get_contents('inc/citeproc-php/style/vancouver.csl');
// $lang = "br";
// $citeproc_abnt = new citeproc($csl_abnt, $lang);
// $mode = "reference";

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
        <script type="text/javascript" src="//cdn.plu.mx/widget-details.js"></script>


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
                    
                    <?php if($total == 0) : ?>

                        <div class="alert alert-info" role="alert">
                        Sua busca não obteve resultado. Você pode refazer sua busca abaixo:<br/><br/>
                            <form action="result.php">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="Pesquise por termo ou autor">
                                    <small id="searchHelp" class="form-text text-muted">Dica: Use * para busca por radical. Ex: biblio*.</small>
                                    <small id="searchHelp" class="form-text text-muted">Dica 2: Para buscas exatas, coloque entre ""</small>
                                    <small id="searchHelp" class="form-text text-muted">Dica 3: Você também pode usar operadores booleanos: AND, OR</small>
                                </div>                       
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
                                
                            </form>
                        </div>
                        <br/><br/>                        
                    
                    <?php endif; ?>

                    <!-- Resultados -->
                        <?php foreach ($cursor["hits"]["hits"] as $r) : ?>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['type'];?>
                                <?php if (!empty($r["_source"]['source'])) : ?>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['source'];?>
                                    <?php (isset($r["_source"]["isPartOf"]["volume"]) ?  print_r(" - v.".$r["_source"]["isPartOf"]["volume"]) : "") ?>
                                    <?php (isset($r["_source"]["isPartOf"]["issue"]) ? print_r(" - n.".$r["_source"]["isPartOf"]["issue"]) : "") ?>
                                    <?php (isset($r["_source"]["isPartOf"]["initialPage"]) ? print_r(" - p.".$r["_source"]["isPartOf"]["initialPage"]) : "") ?>
                                    </h6>
                                <?php endif; ?>
                                <h5 class="card-title"><a class="text-dark" href="<?php echo $r['_source']['url']; ?>"><?php echo $r["_source"]['name']; ?> (<?php echo $r["_source"]['datePublished'];?>)</a></h5>
                                <?php if (!empty($r["_source"]["alternateName"])) : ?>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['alternateName']; ?></h6>
                                <?php endif; ?>

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
                                    <p class="text-muted"><b>Resumo:</b> <?php echo $r["_source"]['description'] ?></p>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['publisher']['organization']['name'])) : ?>
                                    <p class="text-muted"><b>Editora:</b> <?php echo $r["_source"]['publisher']['organization']['name'];?></p>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['ISBN'])) : ?>
                                    <p class="text-muted"><b>ISBN:</b> <?php echo $r["_source"]['ISBN'][0];?></p>
                                <?php endif; ?>                                                                                                 
                                
                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                    <p class="text-muted"><b>DOI:</b> <a href="http://dx.doi.org/<?php echo $r["_source"]['doi'];?>" target="_blank"><?php echo $r["_source"]['doi'];?></a></p>
                                <?php endif; ?>

                                <p class="text-muted"><a class="btn btn-info" href="node.php?_id=<?php echo $r["_id"];?>" target="_blank"><b>Ver registro completo</b></a></p>

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

<?php if (!empty($r["_source"]['references'])) : ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo substr($r["_id"], 1, 6) ?>">
  Ver referências
</button>

<!-- Modal -->
<div class="modal fade" id="<?php echo substr($r["_id"], 1, 6) ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $r["_id"] ?>Title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?php echo $r["_id"] ?>Title">Referências</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
            foreach ($r["_source"]['references'] as $ref) {
                echo ''.$ref["type"].': '.implode("; ", $ref["authors"]).'. '.$ref["name"].'. '.$ref["publisher"].', '.$ref["datePublished"].'.<br/>';
                //print_r($ref);
                //echo "<br/><br/>";
            } 
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>
                                

                                <?php if (!empty($r["_source"]['crossref']['message']['is-referenced-by-count'])) : ?>
                                    
                                <div class="alert alert-success" role="alert">
                                    Quantidade de vezes em que o artigo foi citado: <?php echo $r["_source"]['crossref']['message']['is-referenced-by-count'] ?> (Fonte: Crossref API)
                                </div>

                                <?php endif; ?>                                  

                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                        <div data-badge-popover="right" data-badge-type="1" data-doi="<?php echo $r["_source"]['doi'];?>" data-hide-no-mentions="true" class="altmetric-embed"></div>
                                        <a href="https://plu.mx/plum/a/?doi=<?php echo $r["_source"]['doi'];?>" class="plumx-details" data-hide-when-empty="true" data-badge="true"></a>
                                        <div data-badge-details="right" data-badge-type="2" data-doi="<?php echo $r["_source"]['doi'];?>" data-condensed="true" data-hide-no-mentions="true" class="altmetric-embed"></div>
                                        <div><span class="__dimensions_badge_embed__" data-doi="<?php echo $r["_source"]['doi'];?>" data-hide-zero-citations="true" data-style="small_rectangle"></span></div></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION["login"])) : ?>
                                    <br/><br/>
                                    <form class="form-signin" method="post" action="editor/index.php">
                                        <?php
                                            $jsonRecord = json_encode($r["_source"]);                                        
                                        ?>
                                        <input type="hidden" id="rppbci_id" name="rppbci_id" value="<?php echo $r["_id"] ?>">
                                        <input type="hidden" id="record" name="record" value="<?php echo urlencode($jsonRecord) ?>">
                                        <button class="btn btn-lg btn-warning btn-block" type="submit">Editar registro</button>
                                        <p class="mt-5 mb-3 text-muted"><?= $errorMsg ?></p>
                                    </form>

                                <?php endif; ?>

                                                                                                                                                    
                            
                            </div>
                        </div>


<!-- 

                        <div class="uk-grid-divider uk-padding-small" uk-grid>
                            <div class="uk-width-1-5@m">
                                <a href="result.php?search=source.keyword:&quot;< ?php echo $r["_source"]['source'];?>&quot;">< ?php echo $r["_source"]['source'];?></a>
                            </div>
                            <div class="uk-width-4-5@m">
                                <article class="uk-article">
                                <p class="uk-text-lead uk-margin-remove" style="font-size:115%"><a href="< ?php echo $r['_source']['url'];?>">< ?php echo $r["_source"]['name'];?>< ?php if (!empty($r["_source"]['datePublished'])) { echo ' ('.$r["_source"]['datePublished'].')'; } ?></a></p>

                                        <p class="uk-margin-remove">

                                        </p>


                                        < ?php if (isset($_GET["papel"])) : ?>
                                            < ?php if ($_GET["papel"] == "admin") : ?>
                                                <form class="uk-form uk-form-stacked" action="result.php?search=" method="POST">

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

                                if (!isset($_GET)) {
                                    $_GET = null;
                                }

                                $facets->facet("type", 100, "Tipo", null, "_term", $_GET);
                                $facets->facet("source", 100, "Título do periódico", null, "_term", $_GET);
                                $facets->facet("datePublished", 120, "Ano de publicação", "desc", "_term", $_GET);
                                $facets->facet("author.person.name", 120, "Autores", null, "_term", $_GET);
                                $facets->facet("author.organization.name", 120, "Afiliação", null, "_term", $_GET);
                                $facets->facet_range("numAutores", 100, "Número de autores - Range", $_GET);                                
                                $facets->facet("originalType", 10, "Seções", null, "_term", $_GET);                                
                                $facets->facet("about", 100, "Assuntos", null, "_term", $_GET);
                                $facets->facet("publisher.organization.name", 100, "Editora", null, "_term", $_GET);
                                $facets->facet("isPartOf.name", 100, "Nome do periódico", null, "_term", $_GET);
                                $facets->facet("isPartOf.volume", 100, "Volume", null, "_term", $_GET);
                                $facets->facet("isPartOf.issue", 100, "Fascículo", null, "_term", $_GET);
                                $facets->facet("isPartOf.ISSN", 100, "ISSN", null, "_term", $_GET);
                                $facets->facet("references.authors", 100, "Autores mais citados nas referências", null, "_term", $_GET);
                                $facets->facet("references.datePublished", 100, "Ano de publicação das obras citadas nas referências", null, "_term", $_GET);
                                $facets->facetExistsField("doi", 2, "Possui DOI preenchido?", null, "_term", $_GET);
                                $facets->facet("bookEdition", 100, "Edição", null, "_term", $_GET);
                                $facets->facet("itens.digitalizado", 100, "Digitalizado?", null, "_term", $_GET);
                                $facets->facet("itens.location", 100, "Localização física", null, "_term", $_GET);
                                $facets->facet("itens.organization", 100, "Institução em que se encontra o material", null, "_term", $_GET);
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

