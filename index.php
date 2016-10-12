<html>
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');
            include('inc/meta-header.php');            
        ?>
        <title>Repertório da Produção Periódica de Biblioteconomia e Ciência da Informação - RPPBCI</title>
        <script type="text/javascript" src="inc/uikit/js/components/grid.js"></script>
    </head>    
    <body>
        <div class="uk-container uk-container-center uk-margin-large-top">
            <h1>RPPBCI</h1>
            
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
                    <button class="uk-button">Pesquisar</button>
                    <!--
                    <label><input type="checkbox"> Somente com altmetrics</label>
                    -->
                </fieldset>

            </form>
            
            <br/><br/>
            Quantidade de registros: <?php echo contar_registros($server); ?><br/><br/><br/><br/>
            
            
<ul id="my-id" class="uk-subnav">
    <li data-uk-sort="my-category"><a href="">Título A-Z</a></li>
    <li data-uk-sort="my-category:desc"><a href="">Título Z-A</a></li>
    <li data-uk-sort="my-category2"><a href="">Quantidade de Registros Menor > Maior</a></li>
    <li data-uk-sort="my-category2:desc"><a href="">Quantidade de registros Maior > Menor</a></li>
</ul>

<div data-uk-grid="{controls: '#my-id'}">
    <?php facetas_inicio($server,"journalci_title"); ?>
</div>
            
            
              <br/><br/><br/><br/>  
            
            <?php ultimos_registros($server); ?>
            
                

            
        </div>
    </body>
</html>