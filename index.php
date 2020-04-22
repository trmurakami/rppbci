<!doctype html>
<html lang="en">
<head>

<?php

session_start();

$errorMsg = "";
$validUser = $_SESSION["login"] === true;
if(isset($_POST["username"])) {
  $validUser = $_POST["username"] == "rppbci_admin" && $_POST["password"] == "rppbci_admin";
  if(!$validUser) $errorMsg = "Usuário ou senha inválidos.";
    else $_SESSION["login"] = true;
}

require 'inc/config.php';
require 'inc/meta-header.php';
require 'inc/functions.php';
?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $branch; ?></title>

    <link rel="canonical" href="https://tecbib.com/rppbci">

    <!-- Bootstrap core CSS -->
    <link href="/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <style>
        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        }

        @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
        }

        .jumbotron {
          background-image: url("<?php echo $background_1 ?>");
          background-size: 100%;
          background-repeat: no-repeat;
        }        
    </style>
    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">
</head>
<body>

<!-- NAV -->
<?php require 'inc/navbar.php'; ?>
<!-- /NAV --> 

<main role="main">

    <div class="jumbotron">
        <br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <div class="container">
        <h1 class="display-5"><?php echo $branch; ?></h1>
        <p><?php echo $branch_description; ?></p>

        <form action="result.php">
            <div class="form-group">
                <label for="searchQuery">Termos de busca ou <a href="result.php">ver todos os registros</a></label>
                <input type="text" name="search" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="Pesquise por termo ou autor">
                <small id="searchHelp" class="form-text text-muted">Dica: Use * para busca por radical. Ex: biblio*.</small>
                <small id="searchHelp" class="form-text text-muted">Dica 2: Para buscas exatas, coloque entre ""</small>
                <small id="searchHelp" class="form-text text-muted">Dica 3: Você também pode usar operadores booleanos: AND, OR</small>
            </div>                       
            <button type="submit" class="btn btn-primary">Pesquisar</button>
            
        </form>
        <br/><br/>

        </div>
    </div>

  <div class="container">
    <!-- Example row of columns -->

    <!-- Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="form-signin" method="post" action="index.php">
                <h1 class="h3 mb-3 font-weight-normal">Login</h1>
                <label for="inputUser" class="sr-only">Usuário</label>
                <input type="text" id="inputUser" class="form-control" name="username" placeholder="Usuário" required autofocus>
                <label for="inputPassword" class="sr-only">Senha</label>
                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Senha" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>                   
            </form>
            </div>
            </div>
        </div>
    </div>     
     
    <?php if ($showInitialStats != false) : ?>
    <div class="row">
        <div class="col-md-4">
          <h2>Periódicos indexados</h2>
          <ul class="list-group">
              <?php Homepage::fieldAgg("source", "Artigo"); ?>
          </ul>
          <h2>Eventos indexados</h2>
          <ul class="list-group">
              <?php Homepage::fieldAgg("source", "Trabalho em evento"); ?>
          </ul>          
        </div>
        <div class="col-md-4">
          <h2>Estatísticas</h2>
          <ul class="list-group">
              Total de registros: <?php echo Admin::totalRecords(); ?>
          </ul>
          <ul class="list-group">
              Citações via Crossref API: <a href="result.php?search=crossref.message.is-referenced-by-count:[1 TO *]"><?php echo Homepage::sumFieldAggCrossref(); ?></a>
          </ul>          
        </div>        
        <div class="col-md-4">
          <h2>Interações no facebook</h2>
          <ul class="list-group">
              <?php Homepage::sumFieldAggFacebook(); ?>
          </ul>
        </div> 
      </div>

        <hr>
        <h1>Registros com mais interações</h1>

        <?php Homepage::getLastRecords();?>

    </div>
    <?php endif; ?>

</main>

<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->
</html>