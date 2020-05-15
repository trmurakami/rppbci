<html>
    <head>
        <?php

            require 'inc/config.php'; 
            require 'inc/functions.php';
            require 'inc/meta-header.php';
        ?>
        <title>Sobre o RPPBCI</title>
    </head>    
    <body>
    <main role="main">
        <div class="container">       
        
            <?php require 'inc/navbar.php' ?>
            <br/><br/><br/><br/>
            
            <h2>Estatísticas</h2>

            <?php if (isset($dashboard)) : ?>
                <iframe src="<?php echo $dashboard ?>" height="10000" width="100%" frameBorder="0" scrolling="no"></iframe>
            <?php endif ?>
            
            <?php require 'inc/footer.php'?>
        </div>
        </main>  
    </body>
</html>