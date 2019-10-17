<!doctype html>
<html lang="en">
<head>

<?php
require 'inc/config.php';
require 'inc/meta-header.php';
require 'inc/functions.php';
?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $branch; ?></title>

    <link rel="canonical" href="https://bdpi.usp.br">

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
          background-size: cover;
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
                <label for="searchQuery">Termos de busca</label>
                <input type="text" name="search[]" class="form-control" id="searchQuery" aria-describedby="searchHelp" placeholder="Pesquise por termo ou autor">
                <small id="searchHelp" class="form-text text-muted">Dica: Use * para busca por radical. Ex: biblio*.</small>
            </div>                       
            <button type="submit" class="btn btn-primary">Pesquisar</button>
            
        </form>
        <br/><br/>
        <div class="row">
      <!--
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      -->
        <div class="col-md-4">
          <h2>Periódicos indexados</h2>
          <ul class="list-group">
              <?php Homepage::fieldAgg("source"); ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h2>Estatísticas</h2>
          <ul class="list-group">
              Total de registros: <?php echo Admin::totalRecords(); ?>
          </ul>
        </div>        
        <div class="col-md-4">
          <h2>Interações no facebook</h2>
          <ul class="list-group">
              <?php Homepage::sumFieldAgg("source"); ?>
          </ul>
        </div> 
      </div>

        <br/><br/><br/><br/>


        </div>
    </div>

  <div class="container">
    <!-- Example row of columns -->
     
    

    <hr>
    <h1>Registros com mais interações</h1>

    <?php Homepage::getLastRecords();?>

  </div>

</main>

<!-- FOOTER -->
<?php require 'inc/footer.php'; ?>
<!-- /FOOTER -->
</html>