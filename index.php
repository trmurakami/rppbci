<html>
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');
            include('inc/meta-header.php');            
        ?>
        <title>Repertório da Produção Periódica de Biblioteconomia e Ciência da Informação - RPPBCI</title>
        <script type="text/javascript" src="inc/uikit/js/components/grid.js"></script>
        <script type="text/javascript" src="inc/uikit/js/components/parallax.min.js"></script>
    </head>    
    <body>


    <?php
            if (file_exists("inc/analyticstracking.php")){
                include_once("inc/analyticstracking.php");
            }
        ?>        
        
        <div class="uk-background-image@s uk-background-cover uk-height-viewport" >
            <div class="uk-container">
                <?php 
                    $background_1 = "inc/images/maceio3.jpg";
                    $background_2 = "inc/images/maceio3.jpg";
                    $background_3 = "inc/images/maceio3.jpg"; 
                    $background_number = mt_rand(1, 3);
                    $prefix = "background_";
                ?>    
                <div class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-height-viewport" style="background-image: url(<?php echo ${$prefix . $background_number}; ?>);">
                    <?php include('inc/navbar.php'); ?>
                    <div class="uk-overlay uk-overlay-primary">
                    <h2 style="color:#fcb421"><?php echo $t->gettext(''.$branch.''); ?></h2>                    
                        <form class="uk-form-stacked" action="result.php">

                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-text"><?php echo $t->gettext('Termos de busca'); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="form-stacked-text" type="text" placeholder="<?php echo $t->gettext('Pesquise por título, assunto ou autor'); ?>" name="search[]" data-validation="required">
                                </div>
                            </div>

                            <!--
                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-select"><?php echo $t->gettext('Selecione a base'); ?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="form-stacked-select" name="search[]">
                                        <option disabled selected value><?php echo $t->gettext('Todas as bases'); ?></option>
                                        <option value="base.keyword:&quot;Artigos&quot;" style="color:#333"><?php echo $t->gettext('Artigos'); ?></option>
                                        <option value="base.keyword:&quot;Trabalhos em Eventos&quot;" style="color:#333"><?php echo $t->gettext('Trabalhos em Eventos'); ?></option>
                                    </select>
                                <input type="hidden" name="fields[]" value="titulo">
                                <input type="hidden" name="fields[]" value="autores.*">
                                <input type="hidden" name="fields[]" value="palavras_chave">
                                <input type="hidden" name="fields[]" value="resumo">                                                              
                                </div>
                            </div>
                            -->
                             
                            <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom"><?php echo $t->gettext('Buscar'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
</div> 

<div class="uk-container uk-margin">
    <div class="uk-child-width-1-3@m uk-grid-small uk-grid-match" uk-grid>
        <div>
            <div class="uk-card uk-card-primary uk-card-body">
                <h3 class="uk-card-title">Coleta</h3>
                <p>Os dados dos periódicos disponíveis em OAI-PHM são coletados utilizando a ferramenta Librecat/Catmandu e armazenados em um banco de dados NoSQL ElasticSearch. Os dados são coletados automaticamente, totalizando: <?php echo inicio::contar_registros($server); ?> documentos.</p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Tratamento</h3>
                <p>Os dados são tratados utilizando o webservice do <a href="http://www.labimetrics.inf.br/vocabci/vocab/index.php">Vocabulário Controlado de Ciência da Informação no Brasil</a> que foi criado utilizando o software livre para vocabulários controlados <a href="http://www.vocabularyserver.com/">Tematres</a> e são incluídos dados altmétricos do Facebook recuperados de sua API.</p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h3 class="uk-card-title">Visualização</h3>
                <p>Dados são visualizados por meio de facetas nos resultados de busca. São adicionados gráficos utilizando o Kibana.</p>
            </div>
        </div>
    </div>

</div>

<div class="uk-section uk-container">
            <h1 class="uk-heading-line uk-text-center"><span><?php echo $t->gettext('Periódicos coletados'); ?></span></h1>                    
            <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                <div>
                    <div class="uk-card">
                        <h3 class="uk-card-title"><?php echo $t->gettext('Bases'); ?></h3>
                        <ul class="uk-list uk-list-divider">
                            <?php inicio::facetas_inicio("source"); ?>
                        </ul>                      
                    </div>
                </div>
            </div>

            <hr class="uk-grid-divider">

            <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match uk-grid-divider" uk-grid>
                <div>
                    <h1>Altmetrics (Top 10)</h1>
                    <?php inicio::top_registros(); ?>                
                </div>
                <div>
                    <h1>Altmetrics por periódico</h1>
                    <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=pie&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:segment,type:terms)),listeners:(),params:(addLegend:!t,addTooltip:!t,isDonut:!f,legendPosition:right),title:'New+Visualization',type:pie))" height="600" width="550" scrolling="no" frameborder="0" seamless="seamless"></iframe>                
                </div>
            </div>


</div>       
       
  
    </body>
</html>