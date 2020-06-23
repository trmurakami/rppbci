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

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Para login</strong> use o <strong>usuário: dashboard</strong> e <strong>senha: dashboard</strong>.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php if (isset($dashboard)) : ?>
                <iframe src="<?php echo $dashboard ?>" height="10000" width="100%" frameBorder="0" scrolling="no"></iframe>
            <?php endif ?>
            
            <?php require 'inc/footer.php'?>
        </div>
        </main>  
    </body>
</html>