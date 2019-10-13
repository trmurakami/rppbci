<!DOCTYPE html>
<?php
    include('inc/config.php'); 
    include('inc/functions.php');

    $result_get = get::analisa_get($_GET);
    $query = $result_get['query'];
    $query_facets = $result_get['query'];  
    $limit = $result_get['limit'];
    $page = $result_get['page'];
    $skip = $result_get['skip'];
    
    $params = [];
    $params["index"] = $index;
    $params["type"] = "repository";
    $params["size"] = $limit;
    $params["from"] = $skip;
    $query["query"]["query_string"]["query"] = str_replace("source","name",$query["query"]["query_string"]["query"]);
    $params["body"] = $query;

    $cursor = $client->search($params);
   
    $total = $cursor["hits"]["total"];

    $source = str_replace(' ','+',$cursor["hits"]["hits"][0]["_source"]['name']);

?>
<html>
    <head>
        <?php
            include('inc/meta-header.php'); 
        ?>        
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

        <div class="uk-container">

            <?php include('inc/navbar.php'); ?>
            <br/><br/><br/> 

            <div class="uk-width-1-1@s uk-width-1-1@m">


            <nav class="uk-navbar-container uk-margin" uk-navbar>
                <div class="nav-overlay uk-navbar-left">
                    <a class="uk-navbar-item uk-logo" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#">Clique para uma nova pesquisa</a>
                </div>
                <div class="nav-overlay uk-navbar-right">
                    <a class="uk-navbar-toggle" uk-search-icon uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>
                </div>
                <div class="nav-overlay uk-navbar-left uk-flex-1" hidden>

                <div class="uk-navbar-item uk-width-expand">
                    <form class="uk-search uk-search-navbar uk-width-1-1">
                    <input type="hidden" name="fields[]" value="name">
                    <input type="hidden" name="fields[]" value="author.person.name">
                    <input type="hidden" name="fields[]" value="authorUSP.name">
                    <input type="hidden" name="fields[]" value="about">
                    <input type="hidden" name="fields[]" value="description"> 	    
                    <input class="uk-search-input" type="search" name="search[]" placeholder="Nova pesquisa..." autofocus>
                    </form>
                </div>

                <a class="uk-navbar-toggle" uk-close uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#"></a>

                </div>

            </nav>            
        
            </div>
	   
            <div class="uk-width-1-1@s uk-width-1-1@m">
	    
                <?php if (!empty($_SERVER["QUERY_STRING"])) : ?>                                    
                    <p class="uk-margin-top" uk-margin>
                        <a class="uk-button uk-button-default uk-button-small" href="index.php"><?php echo $t->gettext('Começar novamente'); ?></a>	
                        <?php 
                        
                            if (!empty($_GET["search"])){
                                foreach($_GET["search"] as $filters) {
                                    $filters_array[] = $filters;
                                    $name_field = explode(":",$filters);	
                                    $filters = str_replace($name_field[0].":","",$filters);				
                                    $diff["search"] = array_diff($_GET["search"],$filters_array);						
                                    $url_push = $_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].'?'.http_build_query($diff);
                                    echo '<a class="uk-button uk-button-default uk-button-small" href="http://'.$url_push.'">'.$filters.' <span uk-icon="icon: close; ratio: 1"></span></a>';
                                    unset($filters_array); 	
                                }
                            }	
            
                        ?>
                        
                    </p>
                <?php endif;?>	    
	    
            </div>       

            <div class="uk-grid-divider" uk-grid>
                <div class="uk-width-1-4@s uk-width-2-6@m">
                    <div class="uk-panel uk-panel-box"> 

                        <!-- Facetas - Início -->
                        <h3 class="uk-panel-title">Refinar busca</h3>
                            <hr>
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav="multiple: true">  
                            <?php
                                $facets = new facets();
                                $facets->query = $query_facets;

                                if (!isset($_GET["search"])) {
                                    $_GET["search"] = null;                                    
                                }

                                $facets->facet("autores.nomeCompletoDoAutor",120,"Autores",null,"_term",$_GET["search"]);
                                $facets->facet("autores.afiliacao",120,"Afiliação normalizada",null,"_term",$_GET["search"]);
                                $facets->facet("autores.pais",120,"País da intituição de afiliação",null,"_term",$_GET["search"]);
                                $facets->facet("autores.afiliacao_nao_normalizada",120,"Afiliação não normalizada",null,"_term",$_GET["search"]);
                                $facets->facet("source",100,"Título do periódico",null,"_term",$_GET["search"]);
                                $facets->facet("tipo",10,"Seções",null,"_term",$_GET["search"]);
                                $facets->facet("datePublished",120,"Ano de publicação","desc","_term",$_GET["search"]);
                                $facets->facet("about",100,"Assuntos",null,"_term",$_GET["search"]);
                                $facets->facet("artigoPublicado.nomeDaEditora",100,"Editora",null,"_term",$_GET["search"]);
                                $facets->facet("artigoPublicado.volume",100,"Volume",null,"_term",$_GET["search"]);
                                $facets->facet("artigoPublicado.fasciculo",100,"Fascículo",null,"_term",$_GET["search"]);
                                $facets->facet("artigoPublicado.issn",100,"ISSN",null,"_term",$_GET["search"]);

                                $facets->facet("qualis.qualis.2012.area",50,$t->gettext('Qualis 2010/2012 - Área'),null,"_term",$_GET["search"]);
                                $facets->facet("qualis.qualis.2012.nota",50,$t->gettext('Qualis 2010/2012 - Nota'),null,"_term",$_GET["search"]);                                    
                                $facets->facet("qualis.qualis.2012.area_nota",50,$t->gettext('Qualis 2010/2012 - Área / Nota'),null,"_term",$_GET["search"]);
                                $facets->facet("qualis.qualis.2016.area",50,$t->gettext('Qualis 2013/2016 - Área'),null,"_term",$_GET["search"]);
                                $facets->facet("qualis.qualis.2016.nota",50,$t->gettext('Qualis 2013/2016 - Nota'),null,"_term",$_GET["search"]);                                    
                                $facets->facet("qualis.qualis.2016.area_nota",50,$t->gettext('Qualis 2013/2016 - Área / Nota'),null,"_term",$_GET["search"]);

                                $facets->facet("WOS.coverage",50,$t->gettext('Cobertura na Web of Science'),null,"_term",$_GET["search"]);
                                $facets->facet_range("JCR.JCR.2016.Journal_Impact_Factor",100,"JCR - Journal Impact Factor - 2016");
                                $facets->facet_range("JCR.JCR.2016.IF_without_Journal_Self_Cites",100,"JCR - Journal Impact Factor without Journal Self Cites - 2016");
                                $facets->facet_range("JCR.JCR.2016.Eigenfactor_Score",100,"JCR - Eigenfactor Score - 2016");
                                $facets->facet_range("citescore.citescore.2016.citescore",100,"Citescore - 2016");
                                $facets->facet_range("citescore.citescore.2016.SJR",100,"SJR - 2016");
                                $facets->facet_range("citescore.citescore.2016.SNIP",100,"SNIP - 2016");
           

                                echo '<li>Dados das referências citadas nas publicações</li>';
                                $facets->facet("references.citations.name",100,"Título",null,"_term",$_GET["search"]);
                                $facets->facet("references.citations.analytic.name",100,"Título da publicação fonte",null,"_term",$_GET["search"]);
                                $facets->facet("references.citations.datePublished",100,"Data de publicação",null,"_term",$_GET["search"]);
                                $facets->facet("references.citations.author.person.name.citation",100,"Autor",null,"_term",$_GET["search"]);
                                $facets->facet("references.citations.publisher.organization.location",100,"Local de publicação",null,"_term",$_GET["search"]);
                                $facets->facet("references.citations.publisher.organization.name",100,"Editora",null,"_term",$_GET["search"]);
                                echo '<li>Citações recebidas (Fonte: AMiner)</li>';
                                $facets->facet_range("aminer.num_citation",100,"Citações no AMiner",$_GET["search"]);
                                echo '<li>Registro na BDPI USP</li>';
                                $facets->facet("bdpi.existe",100,"Registrado na BDPI USP?",null,"_term",$_GET["search"]);
                            ?>
                            </ul>
                            <hr>            
            
                </div>
            </div>

            <div class="uk-width-3-4@s uk-width-4-6@m">

                    <hr class="uk-grid-divider">

                    <!-- Resultados -->
                    <div class="uk-width-1-1 uk-margin-top uk-description-list-line">                        
                        <ul class="uk-list uk-list-divider">   
                        <?php $conta_cit = 1; ?>    
                        <?php foreach ($cursor["hits"]["hits"] as $r) : ?>

                        <?php //print_r($r); ?>

                        <div class="uk-grid-divider uk-padding-small" uk-grid>
                            <div class="uk-width-1-1@m">
                                <article class="uk-article">
                                    <p class="uk-text-lead uk-margin-remove" style="font-size:115%"><?php echo $r["_source"]['name'];?></p>

                                    <!-- Qualis - Início -->
                                        <?php if (!empty($r["_source"]["qualis_journal"])): ?>
                                        <div class="uk-alert-primary" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <h5>Informações sobre o Qualis do periódico</h5>
                                            <li class="uk-h6">
                                                <p class="uk-text-small uk-margin-remove">Título: <?php print_r($r["_source"]["qualis_journal"]["title"]); ?></p>
                                                <p class="uk-text-small uk-margin-remove">ISSN: <?php print_r($r["_source"]["qualis_journal"]["issn"][0]); ?></p>

                                                <?php if (!empty($r["_source"]["qualis_journal"]["qualis"]["2012"])): ?>
                                                    <p>Qualis 2010-2012</p>
                                                    <?php foreach ($r["_source"]["qualis_journal"]["qualis"]["2012"] as $metrics_2012) : ?>
                                                        <p class="uk-text-small uk-margin-remove">Área / Nota: <?php print_r($metrics_2012["area_nota"]); ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>  

                                                <?php if (!empty($r["_source"]["qualis_journal"]["qualis"]["2015"])): ?>
                                                    <p>Qualis 2015</p>
                                                    <?php foreach ($r["_source"]["qualis_journal"]["qualis"]["2015"] as $metrics_2015) : ?>
                                                        <p class="uk-text-small uk-margin-remove">Área / Nota: <?php print_r($metrics_2015["area_nota"]); ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <?php if (!empty($r["_source"]["qualis_journal"]["qualis"]["2016"])): ?>
                                                    <p>Qualis 2013-2016</p>
                                                    <?php foreach ($r["_source"]["qualis_journal"]["qualis"]["2016"] as $metrics_2016) : ?>
                                                        <p class="uk-text-small uk-margin-remove">Área / Nota: <?php print_r($metrics_2016["area_nota"]); ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?> 

                                            </li>
                                        </div>
                                        <?php endif; ?>   
                                    <!-- Qualis - Fim -->

                        <!-- JCR - Início -->
                        <!-- < ?php if(!empty($_SESSION['oauthuserdata'])): ?> -->
                        <?php if (!empty($r["_source"]["JCR_jornal"])): ?>
                                <div class="uk-alert-primary" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <h5>Informações sobre o JCR</h5>
                                    <li class="uk-h6">
                                        <p class="uk-text-small uk-margin-remove">Título: <?php print_r($r["_source"]["JCR_jornal"]["title"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">ISSN: <?php print_r($r["_source"]["JCR_jornal"]["issn"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">Journal Impact Factor - 2016: <?php print_r($r["_source"]["JCR_jornal"]["JCR"]["2016"][0]["Journal_Impact_Factor"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">Impact Factor without Journal Self Cites - 2016: <?php print_r($r["_source"]["JCR_jornal"]["JCR"]["2016"][0]["IF_without_Journal_Self_Cites"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">Eigenfactor Score - 2016: <?php print_r($r["_source"]["JCR_jornal"]["JCR"]["2016"][0]["Eigenfactor_Score"]); ?></p>                               
                                        <p class="uk-text-small uk-margin-remove">JCR Rank - 2016: <?php print_r($r["_source"]["JCR_jornal"]["JCR"]["2016"][0]["JCR_Rank"]); ?></p> 
                                    </li>
                                </div>
                            <?php endif; ?>  
                        <!-- < ?php endif; ?> -->
                        <!-- JCR - Fim --> 

                        <!-- Citescore - Início -->
                        <!-- < ?php if(!empty($_SESSION['oauthuserdata'])): ?> -->
                            <?php if (!empty($r["_source"]["citescore_journal"])): ?>
                                <div class="uk-alert-primary" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <h5>Informações sobre o Citescore</h5>
                                    <li class="uk-h6">
                                        <p class="uk-text-small uk-margin-remove">Título: <?php print_r($r["_source"]["citescore_journal"]["title"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">ISSN: <?php print_r($r["_source"]["citescore_journal"]["issn"][0]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">Citescore - 2016: <?php print_r($r["_source"]["citescore_journal"]["citescore"]["2016"][0]["citescore"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">SJR - 2016: <?php print_r($r["_source"]["citescore_journal"]["citescore"]["2016"][0]["SJR"]); ?></p>
                                        <p class="uk-text-small uk-margin-remove">SNIP - 2016: <?php print_r($r["_source"]["citescore_journal"]["citescore"]["2016"][0]["SNIP"]); ?></p>                               
                                        <p class="uk-text-small uk-margin-remove">Open Access: <?php print_r($r["_source"]["citescore_journal"]["citescore"]["2016"][0]["open_access"]); ?></p> 
                                    </li>
                                </div>
                            <?php endif; ?>  
                        <!-- < ?php endif; ?> -->
                        <!-- Citescore - Fim -->                                      



                                </article>
                                <br/><br/>
                                <iframe src="http://143.107.154.38:5601/app/kibana#/dashboard/AV94CQ24dwxasHQlTBf_?embed=true&_g=()&_a=(description:'',filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV80ZuJZdwxasHQlTBT5,key:source.keyword,negate:!f,type:phrase,value:'<?php echo $source; ?>'),query:(match:(source.keyword:(query:'<?php echo $source; ?>',type:phrase))))),options:(darkTheme:!f),panels:!((col:1,id:AV94B6QEdwxasHQlTBf-,panelIndex:1,row:1,size_x:12,size_y:5,type:visualization),(col:1,id:AV98iMNQdwxasHQlTBgE,panelIndex:2,row:6,size_x:12,size_y:7,type:visualization),(col:1,id:AV-bIPk422VNrtV_G9d_,panelIndex:4,row:13,size_x:12,size_y:5,type:visualization),(col:1,id:AV-bEuMw22VNrtV_G9d8,panelIndex:5,row:18,size_x:12,size_y:6,type:visualization),(col:1,id:AV-bIDGW22VNrtV_G9d-,panelIndex:6,row:24,size_x:12,size_y:5,type:visualization),(col:1,id:AV-bJaSG22VNrtV_G9eA,panelIndex:7,row:29,size_x:12,size_y:6,type:visualization)),query:(match_all:()),timeRestore:!f,title:'Estat%C3%ADsticas+por+peri%C3%B3dico',uiState:(P-1:(vis:(legendOpen:!f)),P-4:(vis:(params:(sort:(columnIndex:!n,direction:!n)))),P-5:(vis:(params:(sort:(columnIndex:!n,direction:!n)))),P-6:(vis:(params:(sort:(columnIndex:!n,direction:!n))))),viewMode:view)" height="3500" width="800"></iframe>
                            </div>
                        </div>
                        

                    <?php endforeach;?>
                    </ul>
                    </div>
                    <hr class="uk-grid-divider">

                    
                </div>
            </div>
            <hr class="uk-grid-divider">
<!-- < ?php include('inc/footer.php'); ?> -->         
        </div>
                


        <script>
        $('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){
            var url = window.location.href.split('&page')[0];
            window.location=url +'&page='+ (pageIndex+1);
        });
        </script>    

<?php include('inc/offcanvas.php'); ?>         
        
    </body>
</html>

