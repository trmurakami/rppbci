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
        
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
            
           <nav class="uk-navbar uk-margin-small-bottom">
                <a class="uk-navbar-brand uk-hidden-small" href="index.php">RPPBCI</a>
                <ul class="uk-navbar-nav uk-hidden-small">
                    <li class="uk-active">
                        <a href="index.php">Início</a>
                    </li>
                    <li>
                        <a href="statistics.php">Estatísticas</a>
                    </li>
                    <li>
                        <a href="contact.php">Contato</a>
                    </li>
                    <li>
                        <a href="about.php">Sobre</a>
                    </li>
                </ul>
                <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
                <div class="uk-navbar-brand uk-navbar-center uk-visible-small">RPPBCI</div>
            </nav>                


            <div class="uk-margin uk-text-contrast uk-text-center uk-flex uk-flex-center uk-flex-middle" data-uk-parallax="{bg: '-200'}" style="height: 650px; background-image: url('http://bdpife2.sibi.usp.br/rppbci/inc/images/maceio.jpg'); background-size: 1223px 612px; background-repeat: no-repeat; background-position: 50% -22.61px;">
                    
                                <div class="uk-vertical-align-middle uk-medium-1-2">
                                    <h1 class="uk-heading-large" style="color:fff;">RPPBCI</h1>
                                    <p class="uk-text-large">Repertório da Produção Periódica Brasileira de Ciência da Informação disponível em OAI-PMH.</p>
                                    <form class="uk-form" action="result.php" method="get">

                                        <fieldset data-uk-margin>
                                            <legend>Faça uma busca</legend>
                                            <input type="text" placeholder="" name="search_index">
                                            <!--
                                            <select>
                                                <option>Títulos e autores</option>
                                                <option>Referências</option>
                                            </select>
                                            -->
                                            <button class="uk-button uk-button-primary uk-button-large">Pesquisar</button>
                                            <!--
                                            <label><input type="checkbox"> Somente com altmetrics</label>
                                            -->
                                        </fieldset>

                                    </form>      
                                </div>                      
                    

             </div>    
                        
           <hr class="uk-grid-divider">
            
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
                            <p>Dados são visualizados por meio de facetas nos resultados de busca. São adicionados gráficos utilizando as bibliotecas Sigma.js e Google Charts.</p>
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

            <hr class="uk-grid-divider">

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-2">
                    <h1>Top 5 registros com altmetrics</h1>
                    <?php ultimos_registros($server); ?>
                </div>

                <div class="uk-width-medium-1-2">
                    <img width="660" height="400" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iNjYwcHgiIGhlaWdodD0iNDAwcHgiIHZpZXdCb3g9IjAgMCA2NjAgNDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA2NjAgNDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGNUY1RjUiIHdpZHRoPSI2NjAiIGhlaWdodD0iNDAwIi8+DQo8ZyBvcGFjaXR5PSIwLjciPg0KCTxwYXRoIGZpbGw9IiNEOEQ4RDgiIGQ9Ik0yNTguMTg0LDE0My41djExM2gxNDMuNjMydi0xMTNIMjU4LjE4NHogTTM5MC4yNDQsMjQ0LjI0N0gyNzAuNDM3di04OC40OTRoMTE5LjgwOEwzOTAuMjQ0LDI0NC4yNDcNCgkJTDM5MC4yNDQsMjQ0LjI0N3oiLz4NCgk8cG9seWdvbiBmaWxsPSIjRDhEOEQ4IiBwb2ludHM9IjI3Ni44ODEsMjM0LjcxNyAzMDEuNTcyLDIwOC43NjQgMzEwLjgyNCwyMTIuNzY4IDM0MC4wMTYsMTgxLjY4OCAzNTEuNTA1LDE5NS40MzQgDQoJCTM1Ni42ODksMTkyLjMwMyAzODQuNzQ2LDIzNC43MTcgCSIvPg0KCTxjaXJjbGUgZmlsbD0iI0Q4RDhEOCIgY3g9IjMwNS40MDUiIGN5PSIxNzguMjU3IiByPSIxMC43ODciLz4NCjwvZz4NCjwvc3ZnPg0K" alt="">
                </div>
            </div>

            <hr class="uk-grid-divider">

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="uk-panel uk-panel-box uk-text-center">
                        <p><strong>Phasellus viverra nulla ut metus.</strong> Quisque rutrum etiam ultricies nisi vel augue.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </body>
</html>