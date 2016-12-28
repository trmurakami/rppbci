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
        <div class="uk-block uk-block-large uk-cover-background uk-flex uk-flex-middle uk-height-viewport uk-contrast" style="background-image: url('inc/images/maceio3.jpg');" >
            
            <div class="uk-container uk-container-center">

                <section class="uk-grid uk-grid-match" data-uk-grid-margin>
                     
                    <div class="uk-vertical-align-middle uk-width-medium-1-1 uk-panel  uk-text-center">
                        <h1 class="uk-heading-large" style="color:fff;">RPPBCI</h1>
                        <p class="uk-text-large uk-margin-large-bottom">Repertório da Produção Periódica Brasileira de Ciência da Informação disponível em OAI-PMH.</p>
                        <form class="uk-form" action="result.php" method="get">

                            <fieldset data-uk-margin>
                                <legend>Faça uma busca</legend>
                                
                                <input type="text" placeholder="Busque no campo título, autor ou no resumo" name="search[]" class="uk-form-large uk-form-width-large ">
                                <!--
                                <select>
                                    <option>Títulos e autores</option>
                                    <option>Referências</option>
                                </select>
                                -->
                                <input type="hidden" name="fields[]" value="title">
                                <input type="hidden" name="fields[]" value="creator">
                                <input type="hidden" name="fields[]" value="subject">
                                <input type="hidden" name="fields[]" value="description">				
                                <button class="uk-button uk-button-primary uk-button-large">Pesquisar <i class="uk-icon-search"></i></button>
                                <!--
                                <label><input type="checkbox"> Somente com altmetrics</label>
                                -->
                            </fieldset>

                        </form>      
                    </div>                      
                    
                </section>

            </div>
        </div>
        
        
        <div id="tm-main" class="tm-main uk-block uk-block-default">
            <div class="uk-container uk-container-center">

                <div class="uk-grid" data-uk-grid-match data-uk-grid-margin>

                    <main class="uk-width-1-1">
                        <article class="uk-article">

    
                           <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-3">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-6">
                                            <i class="uk-icon-cloud-download uk-icon-large uk-text-primary"></i>
                                        </div>
                                        <div class="uk-width-5-6">
                                            <h2 class="uk-h3">Coleta</h2>
                                            <p>Os dados dos periódicos disponíveis em OAI-PHM são coletados utilizando a ferramenta Librecat/Catmandu e armazenados em um banco de dados NoSQL ElasticSearch. Os dados são coletados automaticamente, totalizando: <?php echo contar_registros($server); ?> documentos </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-3">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-6">
                                            <i class="uk-icon-thumbs-o-up uk-icon-large uk-text-primary"></i>
                                        </div>
                                        <div class="uk-width-5-6">
                                            <h2 class="uk-h3">Tratamento</h2>
                                            <p>Os dados são tratados utilizando o webservice do <a href="http://www.labimetrics.inf.br/vocabci/vocab/index.php">Vocabulário Controlado de Ciência da Informação no Brasil</a> que foi criado utilizando o software livre para vocabulários controlados <a href="http://www.vocabularyserver.com/">Tematres</a> e são incluídos dados altmétricos do Facebook recuperados de sua API.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-3">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-6">
                                            <i class="uk-icon-dashboard uk-icon-large uk-text-primary"></i>
                                        </div>
                                        <div class="uk-width-5-6">
                                            <h2 class="uk-h3">Visualização</h2>
                                            <p>Dados são visualizados por meio de facetas nos resultados de busca. São adicionados gráficos utilizando o Kibana.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="uk-grid-divider">

                            <div class="uk-grid" data-uk-grid-margin>

                                <div class="uk-width-medium-1-1">
                                    <h1>Periódicos coletados</h1>
                                    <ul id="my-id" class="uk-subnav">
                                        <li data-uk-sort="my-category"><a href="">Título A-Z</a></li>
                                        <li data-uk-sort="my-category:desc"><a href="">Título Z-A</a></li>
                                        <li data-uk-sort="my-category2"><a href="">Quantidade de Registros Menor > Maior</a></li>
                                        <li data-uk-sort="my-category2:desc"><a href="">Quantidade de registros Maior > Menor</a></li>
                                    </ul>
                                    <div data-uk-grid="{controls: '#my-id'}">
                                        <?php facetas_inicio($server,"journalci_title"); ?>
                                    </div>                                    
                                </div>
 

                            </div>
                            
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <h1>Top 10 registros com altmetrics</h1>
                                    <?php ultimos_registros($server); ?>
                                </div>
                                <!--
                                <div class="uk-width-medium-1-2">
                                    <h1>Altmetrics por periódico</h1>
                                    <iframe src="http://bdpife2.sibi.usp.br:5601/goto/3de7cdf52f42e4e20f7fa1c5ad95b08e" height="600" width="550" scrolling="no" frameborder="0" seamless="seamless"></iframe>                    
                                </div>
                                -->
                            </div>

                            <hr class="uk-grid-divider">

                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <div class="uk-panel uk-panel-box uk-text-center">
                                        <p><strong>Phasellus viverra nulla ut metus.</strong> Quisque rutrum etiam ultricies nisi vel augue.</p>
                                    </div>
                                </div>
                            </div>                            
                      
                        </article>
                    </main>

                    
                </div>

            </div>
        </div>

        
                <div class="uk-block uk-block-secondary uk-contrast">
            <div class="uk-container uk-container-center">

                <section class="uk-grid uk-grid-match" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">

    <div class="uk-panel   ">

        
        <ul class="uk-grid uk-grid-medium uk-flex uk-flex-center">
    <li><a href="#" class="uk-icon-hover uk-icon-small uk-icon-twitter"></a></li>
    <li><a href="#" class="uk-icon-hover uk-icon-small uk-icon-facebook "></a></li>
</ul>

<ul class="uk-subnav uk-margin uk-flex uk-flex-center">
    <li><a href="#">Laboratório de Estudos Métricos da Informação na Web (Lab-iMetrics)</a></li>
    <li><a href="about.php">Sobre</a></li>
</ul>
    </div>

</div>
                </section>

            </div>
        </div>
        
          <?php include('inc/offcanvas.php')?>        
  
    </body>
</html>