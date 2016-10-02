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
            
            <?php echo contar_registros($server); ?>
            
            <?php ultimos_registros($server); ?>
            
        </div>
    </body>
</html>