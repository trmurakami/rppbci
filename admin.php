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
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
            <?php include('inc/navbar.php')?>

            <h2>Administração</h2>

                <form class="uk-form" action="harvester.php" method="get">

                    <fieldset data-uk-margin>
                        <legend>Adicione uma nova fonte</legend>

                        <input type="text" placeholder="Incluir o endereço do oai" name="oai" class="uk-form-large uk-form-width-large">
                        <input type="text" placeholder="Set_oai. Em branco por padrão" name="set" class="uk-form-large uk-form-width-large">
                        <input type="text" placeholder="Informe o Qualis 2015 da publicação" name="qualis2015" class="uk-form-large uk-form-width-large">
                        <input type="text" placeholder="Informe a Área de Conhecimento" name="area" class="uk-form-large uk-form-width-large">
                        <input type="text" placeholder="Informe a Área de Conhecimento - Nível 2" name="areaChild" class="uk-form-large uk-form-width-large">
                        <input type="text" placeholder="Informe se o Periódico é corrente ou não corrente" name="corrente" class="uk-form-large uk-form-width-large">
                        <select name="metadataFormat">
                            <option value="nlm">nlm (padrão)</option>
                            <option value="rfc1807">rfc1807</option>
                        </select>                        
                        <button class="uk-button uk-button-primary uk-button-large">Inserir</button>
                    </fieldset>

                </form>


            <h3>Fontes coletadas</h3>


            <?php Admin::sources("source"); ?>
        </div>

    </body>
</html>
