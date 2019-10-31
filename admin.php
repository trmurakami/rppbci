<?php

session_start();
if(!$_SESSION["login"]) {
    header("Location: login.php"); die();
}

?>

<html>
    <head>
        <?php
            include('inc/config.php');
            include('inc/functions.php');
            include('inc/meta-header.php');
        ?>
        <title>Administração</title>
        <script type="text/javascript" src="inc/uikit/js/components/grid.js"></script>
        <script type="text/javascript" src="inc/uikit/js/components/parallax.min.js"></script>
    </head>
    <body>
    <main role="main">
        <div class="container">    
        
            <?php include('inc/navbar.php')?>
            <br/><br/><br/><br/>

            <h2>Administração</h2>

                <form action="harvester.php" method="get">

                    <div class="form-group">
                        <label for="oai">OAI</label>
                        <input type="text" class="form-control" id="oai" aria-describedby="oaiHelp" name="oai" placeholder="Incluir a URL do OAI">
                        <small id="oaiHelp" class="form-text text-muted">Incluir a URL do OAI</small>
                    </div>

                    <div class="form-group">
                        <label for="set">SET-OAI</label>
                        <input type="text" class="form-control" id="set" aria-describedby="setHelp" name="set" placeholder="Incluir um SET-OAI. Em branco por padrão">
                        <small id="setHelp" class="form-text text-muted">Incluir um SET-OAI. Em branco por padrão</small>
                    </div>

                    <div class="form-group">
                        <label for="area">Área de Conhecimento</label>
                        <input type="text" class="form-control" id="area" aria-describedby="areaHelp" name="area" placeholder="Informe a Área de Conhecimento">
                        <small id="areaHelp" class="form-text text-muted">Informe a Área de Conhecimento</small>
                    </div>

                    <div class="form-group">
                        <label for="areaChild">Área de Conhecimento - Nível 2</label>
                        <input type="text" class="form-control" id="areaChild" aria-describedby="areaChildHelp" name="areaChild" placeholder="Informe a Área de Conhecimento - Nível 2">
                        <small id="areaChildHelp" class="form-text text-muted">Informe a Área de Conhecimento - Nível 2</small>
                    </div>

                    <div class="form-group">
                        <label for="corrente">Corrente / Não corrente</label>
                        <select class="form-control" id="corrente" name="corrente">
                            <option value="corrente">corrente</option>
                            <option value="não corrente">não corrente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="metadataFormat">Formato de metadados</label>
                        <select class="form-control" id="metadataFormat" name="metadataFormat">
                            <option value="nlm">nlm (padrão)</option>
                            <option value="rfc1807">rfc1807</option>
                            <option value="oai_dc">oai_dc</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">Inserir</button>  

                </form>

            <h3>Estatísticas</h3>
            <p>Total de registros: <?php echo Admin::totalRecords(); ?></p>                
            <h3>Status da coleta do Facebook</h3>
            <p>Registros coletados no Facebook: <?php echo Admin::harvestStatus("facebook"); ?></p>
            <p><a href="tools/coleta_facebook.php">Coletar facebook</a></p>

            <h3>Crossref</h3>
            <p>Total de registros com doi: <?php echo Admin::harvestStatus("doi"); ?></p>
            <p>Registros coletados na Crossref: <?php echo Admin::harvestStatus("crossref"); ?></p>
            <p><a href="tools/crossref.php">Coletar Crossref</a></p>

            <h3>Fontes coletadas</h3>
            <div class="uk-alert-primary" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><a href="tools/export.php">Exportar todos os registros</a></p>
            </div>
            <?php Admin::sources("source"); ?>
            <?php require 'inc/footer.php'?>
        </div>

    </main>

    </body>
</html>
