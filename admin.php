<html>
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');
            include('inc/meta-header.php');            
        ?>
        <title>Administração - RPPBCI</title>
        <script type="text/javascript" src="inc/uikit/js/components/grid.js"></script>
        <script type="text/javascript" src="inc/uikit/js/components/parallax.min.js"></script>
    </head>    
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
            <?php include('inc/navbar.php')?>
            
            <h2>Administração - RPPBCI</h2>
            
                <form class="uk-form" action="harvester.php" method="get">

                    <fieldset data-uk-margin>
                        <legend>Adicione uma nova fonte</legend>

                        <input type="text" placeholder="Incluir o endereço do oai" name="oai" class="uk-form-large uk-form-width-large">
                        <button class="uk-button uk-button-primary uk-button-large">Inserir</button>
                    </fieldset>

                </form>                
            
            
            <h3>Fontes coletadas</h3>
        
        
            <?php admin::sources("source"); ?>
        </div>    
        
    </body>
</html>