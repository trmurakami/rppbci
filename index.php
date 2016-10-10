<html>
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');
            include('inc/meta-header.php');
        ?>
        <title>Repertório da Produção Periódica de Biblioteconomia e Ciência da Informação - RPPBCI</title>
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
            
            <?php facetas_inicio($server,"journalci_title"); ?>  <br/><br/><br/><br/>  
            
            <?php ultimos_registros($server); ?>
            
                

            
        </div>
    </body>
</html>