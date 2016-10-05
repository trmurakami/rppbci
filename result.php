
<!DOCTYPE html>
<?php
    include('inc/config.php'); 
    include('inc/functions.php');
    $result_get = analisa_get($_GET);
    $query_complete = $result_get['query_complete'];
    $query_aggregate = $result_get['query_aggregate'];
    $escaped_url = $result_get['escaped_url'];
    $limit = $result_get['limit'];
    $page = $result_get['page'];
    $new_get = $result_get['new_get'];
    $cursor = query_elastic($query_complete,$server);
    $total = $cursor["hits"]["total"];

   /* Citeproc-PHP*/
    include 'inc/citeproc-php/CiteProc.php';
    $csl_abnt = file_get_contents('inc/citeproc-php/style/abnt.csl');
    $csl_apa = file_get_contents('inc/citeproc-php/style/apa.csl');
    $csl_nlm = file_get_contents('inc/citeproc-php/style/nlm.csl');
    $csl_vancouver = file_get_contents('inc/citeproc-php/style/vancouver.csl');
    $lang = "br";
    $citeproc_abnt = new citeproc($csl_abnt,$lang);
    $citeproc_apa = new citeproc($csl_apa,$lang);
    $citeproc_nlm = new citeproc($csl_nlm,$lang);
    $citeproc_vancouver = new citeproc($csl_nlm,$lang);
    $mode = "reference";

?>
<html>
    <head>
        <?php
            include('inc/meta-header.php'); 
        ?>        
        <title>RPPBCI - Resultado da busca</title>
        <script src="inc/uikit/js/components/accordion.min.js"></script>
        <script src="inc/uikit/js/components/pagination.min.js"></script>
        <script src="inc/uikit/js/components/datepicker.min.js"></script>
        
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
        <?php include_once("inc/analyticstracking.php") ?>  
        <?php include('inc/navbar.php'); ?>        
        
        <div class="uk-container uk-container-center">
            <div class="uk-grid" data-uk-grid>                        
                <div class="uk-width-small-1-2 uk-width-medium-2-6">                    
                    

<div class="uk-panel uk-panel-box">
    <form class="uk-form" method="get" action="result.php">
    <fieldset>
        <legend>Filtros ativos</legend>
        <?php foreach ($new_get as $key => $value) : ?>
            <div class="uk-form-row">
                <label><?php echo $key; ?>: <?php echo implode(",",$value); ?></label>
                <input type="checkbox" checked="checked"  name="<?php echo $key; ?>[]" value="<?php echo implode(",",$value); ?>">
            </div>
        <?php endforeach;?>
        <?php if (!empty($result_get['termo_consulta'])): ?>
            <div class="uk-form-row">
                <label>Consulta: <?php echo $result_get['termo_consulta']; ?></label>
                <input type="checkbox" checked="checked"  name="search_index" value="<?php echo $result_get['termo_consulta']; ?>">
            </div>
        <?php endif; ?>      
        <div class="uk-form-row"><button type="submit" class="uk-button-primary">Retirar filtros</button></div>
    </fieldset>        
    </form>    
    <hr>
    <h3 class="uk-panel-title">Refinar meus resultados</h3>    
    <ul class="uk-nav uk-nav-side uk-nav-parent-icon uk-margin-top" data-uk-nav="{multiple:true}">
        <hr>
     
    <?php 
        gerar_faceta($query_aggregate,$escaped_url,$server,tipo,10,"Tipo de material");
        gerar_faceta($query_aggregate,$escaped_url,$server,journalci_title,100,"Título do periódico");                    
        gerar_faceta($query_aggregate,$escaped_url,$server,creator,120,"Autores");
        gerar_faceta($query_aggregate,$escaped_url,$server,creator_total,100,"Quantidade de autores");        
        gerar_faceta($query_aggregate,$escaped_url,$server,year,120,"Ano de publicação","desc");
        gerar_faceta($query_aggregate,$escaped_url,$server,subject,100,"Assuntos");
        gerar_faceta($query_aggregate,$escaped_url,$server,language,40,"Idioma");
        gerar_faceta($query_aggregate,$escaped_url,$server,publisher,100,"Editora");
        gerar_faceta($query_aggregate,$escaped_url,$server,format,100,"Formato");
        gerar_faceta($query_aggregate,$escaped_url,$server,ISSN,100,"ISSN");        
        gerar_faceta($query_aggregate,$escaped_url,$server,qualis2014,200,"Qualis 2014");
        gerar_faceta($query_aggregate,$escaped_url,$server,coverage,30,"Cobertura");
    ?>
        
    </ul>

    <hr>            
            
</div>
    
                    

                    
                </div>
                <div class="uk-width-small-1-2 uk-width-medium-4-6">
                    
                <div class="uk-alert" data-uk-alert>
                    <a href="" class="uk-alert-close uk-close"></a>
                
                    
                        <?php $ano_bar = generateDataGraphBar($server, $url, $query_aggregate, 'year', "_term", 'desc', 'Ano', 10); ?>
                    
                        <div id="ano_chart" class="uk-visible-large"></div>
                        <script type="application/javascript">
                            var graphdef = {
                                categories : ['Ano'],
                                dataset : {
                                    'Ano' : [<?= $ano_bar; ?>]
                                }
                            }
                            var chart = uv.chart ('Bar', graphdef, {
                                meta : {
                                    position: '#ano_chart',
                                    caption : 'Ano de publicação',
                                    hlabel : 'Ano',
                                    vlabel : 'Registros'
                                },
                                graph : {
                                    orientation : "Vertical"
                                },
                                dimension : {
                                    width: 600,
                                    height: 140
                                }
                            })
                        </script>                        
                 </div>                 

                    
                    <div class="uk-grid uk-margin-top">
                        <div class="uk-width-1-3">                        
                        <ul class="uk-subnav uk-nav-parent-icon uk-subnav-pill">
                            <li>Ordenar por:</li>

                            <!-- This is the container enabling the JavaScript -->
                            <li data-uk-dropdown="{mode:'click'}">

                                <!-- This is the nav item toggling the dropdown -->
                                <a href="">Data (Novos)</a>

                                <!-- This is the dropdown -->
                                <div class="uk-dropdown uk-dropdown-small">
                                    <ul class="uk-nav uk-nav-dropdown">
                                        <li><a href="">Data (Antigos)</a></li>
                                        <li><a href="">Título</a></li>
                                    </ul>
                                </div>

                            </li>
                        </ul>                        
                            
                        </div>
                        <div class="uk-width-1-3"><p class="uk-text-center"><?php print_r(number_format($total,0,',','.'));?> registros</p></div>
                        <div class="uk-width-1-3">
                            <ul class="uk-pagination" data-uk-pagination="{items:<?php print_r($total);?>,itemsOnPage:<?php print_r($limit);?>,displayedPages:3,edges:1,currentPage:<?php print_r($page-1);?>}"></ul>                         
                        </div>
                    </div>
                    
                    <hr class="uk-grid-divider">
                    <div class="uk-width-1-1 uk-margin-top uk-description-list-line">
                    <ul class="uk-list uk-list-line">
                    <?php $conta_cit = 1; ?>    
                    <?php foreach ($cursor["hits"]["hits"] as $r) : ?>
                                        
                        <li>                        
                            <div class="uk-grid uk-flex-middle" data-uk-grid-   margin="">
                                <div class="uk-width-medium-2-10 uk-row-first">
                                    <div class="uk-panel uk-h6 uk-text-break">
                                        <a href="result.php?type[]=<?php echo $r["_source"]['type'];?>"><?php echo ucfirst(strtolower($r["_source"]['type']));?></a>
                                    </div>
                                    <div class="uk-panel uk-h6 uk-text-break">

                                      
                                    </div>
                                    
                                    <div class="uk-panel uk-h6 uk-text-break">

                                      
                                    </div>                                     
                                    
                                </div>
                                <div class="uk-width-medium-8-10 uk-flex-middle">
                                    
                                    <ul class="uk-list">
                                        <li class="uk-margin-top uk-h4">
                                            <strong><a href="single.php?_id=<?php echo  $r['_id'];?>"><?php echo $r["_source"]['title'][0];?> (<?php echo $r["_source"]['year'][0]; ?>)</a></strong>
                                        </li>
                                        <li class="uk-h6">
                                            Autores:
                                            <?php if (!empty($r["_source"]['creator'])) : ?>
                                            <?php foreach ($r["_source"]['creator'] as $autores) {
                                                $authors_array[]='<a href="result.php?authors[]='.$autores.'">'.$autores.'</a>';
                                            } 
                                           $array_aut = implode(", ",$authors_array);
                                            unset($authors_array);
                                            print_r($array_aut);
                                            ?>
                                            
                                           
                                            <?php endif; ?>                           
                                        </li>
                                        
                                        <?php if (!empty($r["_source"]['ispartof'])) : ?><li class="uk-h6">In: <a href="result.php?ispartof[]=<?php echo $r["_source"]['ispartof'];?>"><?php echo $r["_source"]['ispartof'];?></a></li><?php endif; ?>
                            
                                        <li class="uk-h6">
                                            Assuntos:
                                            <?php if (!empty($r["_source"]['subject'])) : ?>
                                            <?php foreach ($r["_source"]['subject'] as $assunto) : ?>
                                                <a href="result.php?subject[]=<?php echo $assunto;?>"><?php echo $assunto;?></a>
                                            <?php endforeach;?>
                                            <?php endif; ?>
                                        </li>
                                        <?php if (!empty($r["_source"]['fatorimpacto'])) : ?>
                                        <li class="uk-h6">
                                            <p>Fator de impacto da publicação: <?php echo $r["_source"]['fatorimpacto'][0]; ?></p>
                                        </li>
                                        <?php endif; ?>
                                        <li>
                                            <?php if (!empty($r["_source"]['url_principal'])||!empty($r["_source"]['doi'])) : ?>
                                            <div class="uk-button-group" style="padding:15px 15px 15px 0;">     
                                                <?php if (!empty($r["_source"]['url_principal'])) : ?>
                                                <a class="uk-button-small uk-button-primary" href="<?php echo $r["_source"]['url_principal'];?>" target="_blank">Acesso online à fonte</a>
                                                <?php endif; ?>
                                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                                <a class="uk-button-small uk-button-primary" href="http://dx.doi.org/<?php echo $r["_source"]['doi'];?>" target="_blank">Resolver DOI</a>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </li>
                                        
                                        <li class="uk-h6 uk-margin-top">
                                            <p>Métricas:</p>
                                            <ul>
                                                <li>
                                                    <div data-badge-popover="right" data-badge-type="1" data-doi="<?php echo $r["_source"]['doi'];?>" data-hide-no-mentions="true" class="altmetric-embed"></div>
                                                </li>
                                                <li>
                                                    <a href="https://plu.mx/plum/a/?doi=<?php echo $r["_source"]['doi'];?>" class="plumx-plum-print-popup" data-hide-when-empty="true" data-badge="true"></a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>
                                                    <?php facebook_altmetrics($r["_source"]['url_principal'],$facebook_token); ?>
                                                </li>
                                            </ul>
                                        </li>
                                        <a href="#" data-uk-toggle="{target:'#citacao<?php echo $conta_cit;?>'}">Citar</a>                                        
                                        <div id="citacao<?php echo $conta_cit;?>" class="uk-hidden">
                                            <?php $conta_cit++; ?>
                                        <li class="uk-h6 uk-margin-top">
                                            <div class="uk-alert uk-alert-danger">A citação é gerada automaticamente e pode não estar totalmente de acordo com as normas</div>
                                            <ul>
                                                <li class="uk-margin-top">
                                                    <p><strong>ABNT</strong></p>
                                                    <?php                                                        
                                                        $data = gera_consulta_citacao($r["_source"]);
                                                        print_r($citeproc_abnt->render($data, $mode));
                                                    ?>
                                                </li>                                               
                                            </ul>                                              
                                        </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach;?>
                    </ul>
                    </div>
                    <hr class="uk-grid-divider">
                    <div class="uk-grid uk-margin-top">
                        <div class="uk-width-1-2"><p class="uk-text-center"><?php print_r($total);?> registros</p></div>
                        <div class="uk-width-1-2">
                            <ul class="uk-pagination" data-uk-pagination="{items:<?php print_r($total);?>,itemsOnPage:<?php print_r($limit);?>,displayedPages:3,edges:1,currentPage:<?php print_r($page-1);?>}"></ul>                         
                        </div>
                    </div>                   
                    

                    
                </div>
            </div>
            <hr class="uk-grid-divider">
<?php include('inc/footer.php'); ?>          
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

