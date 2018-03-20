<!--
           <nav class="uk-navbar uk-margin-small-bottom">                
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

-->

<div class="uk-position-top">
<div class="uk-visible@m">
    <div class="uk-navbar uk-container uk-navbar-container uk-margin uk-navbar-transparent" uk-navbar="dropbar: true; dropbar-mode: push; mode: click">      
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li class="uk-active"><a href="index.php"><?php echo $t->gettext('Início'); ?></a></li>
                <li class="uk-active">
                    <a href="statistics.php"><?php echo $t->gettext('Estatísticas'); ?></a>     
                </li> 
             </ul>
        </div>

        <div class="uk-navbar-center">
            <a class="uk-navbar-item uk-logo" href="index.php"></a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">                           
                <li class="uk-active">
                    <a href="about.php"><?php echo $t->gettext('Sobre'); ?></a>     
                </li>                
                <?php if ($_SESSION['localeToUse'] == 'en_US') : ?>
                    <li><a href="http://<?php echo ''.$_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].''; ?>?<?php echo $_SERVER["QUERY_STRING"]; ?>&locale=pt_BR">Português</a></li>
                <?php else : ?>
                    <li><a href="http://<?php echo ''.$_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"].''; ?>?<?php echo $_SERVER["QUERY_STRING"]; ?>&locale=en_US">English</a></li>
                <?php endif ; ?>                
                

            </ul>
        </div>            
    </div>
</div>


<div class="uk-hidden@m">
    <div class="uk-offcanvas-content">

        <button class="uk-button uk-button-default uk-margin-small-right" type="button" uk-toggle="target: #offcanvas-nav-primary">Menu</button>

        <div id="offcanvas-nav-primary" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar uk-flex uk-flex-column">

                <ul class="uk-nav uk-nav-primary uk-nav-center uk-margin-auto-vertical">
                    <li class="uk-active"><a href="index.php"><?php echo $t->gettext('Início'); ?></a></li>
                    <li class="uk-active"><a href="advanced_search.php"><?php echo $t->gettext('Busca avançada'); ?></a></li>
                    <li class="uk-nav-divider"></li>
                    <li class="uk-active"><a href="statistics.php"><?php echo $t->gettext('Estatísticas'); ?></a></li>
                    <li class="uk-active"><a href="contact.php"><?php echo $t->gettext('Contato'); ?></a></li>
                    <li class="uk-active"><a href="about.php"><?php echo $t->gettext('Sobre'); ?></a></li>
                </ul>

            </div>
        </div>
    </div>
</div>

</div> 