<?php
/**
 * Item page
 */

require 'inc/config.php';
require 'inc/functions.php';


/* Citeproc-PHP*/
require 'inc/citeproc-php/CiteProc.php';
$csl_abnt = file_get_contents('inc/citeproc-php/style/ecausp-abnt.csl');
//$csl_abnt = file_get_contents('inc/citeproc-php/style/ufrgs.csl');
$lang = "pt-BR";
$citeproc_abnt = new citeproc($csl_abnt, $lang);
$mode = "reference";


/* QUERY */
$cursor = Elasticsearch::get($_GET['_id'], null);

print_r($cursor);

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>

    <?php
    require 'inc/meta-header.php';
    ?>   
    <title><?php echo $branch_abrev; ?> - Detalhe do registro: <?php echo $cursor["_source"]['name'];?></title>

</head>
<body>
    <?php
    if (file_exists("inc/analyticstracking.php")) {
        include_once "inc/analyticstracking.php";
    }
    ?>
    <!-- TOP -->
    <div class="top-wrap uk-position-relative uk-background-secondary">
        <div class="uk-section uk-section-default" style="padding:0">

            <!-- NAV -->
            <?php require 'inc/navbar.php'; ?>
            <!-- /NAV -->
        
            <div class="uk-container">
                <div class="container">
                    <div class="row">
                        <div class="col-8">

                        <?php $r = $cursor; ?>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['type'];?>
                                <?php if (!empty($r["_source"]['source'])) : ?>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['source'];?>
                                    <?php (isset($r["_source"]["isPartOf"]["volume"]) ?  print_r(" - v.".$r["_source"]["isPartOf"]["volume"]) : "") ?>
                                    <?php (isset($r["_source"]["isPartOf"]["issue"]) ? print_r(" - n.".$r["_source"]["isPartOf"]["issue"]) : "") ?>
                                    <?php (isset($r["_source"]["isPartOf"]["initialPage"]) ? print_r(" - p.".$r["_source"]["isPartOf"]["initialPage"]) : "") ?>
                                    </h6>
                                <?php endif; ?>
                                <h5 class="card-title"><a class="text-dark" href="<?php echo $r['_source']['url']; ?>"><?php echo $r["_source"]['name']; ?> (<?php echo $r["_source"]['datePublished'];?>)</a></h5>
                                <?php if (!empty($r["_source"]["alternateName"])) : ?>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $r["_source"]['alternateName']; ?></h6>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]["author"])) : ?>
                                    <?php 
                                        foreach ($r["_source"]["author"] as $autores) {
                                            if (!empty($autores["organization"]["name"])){
                                                $authors_array[]=''.$autores["person"]["name"].' ('.$autores["organization"]["name"].')';
                                            } else {
                                                $authors_array[]=''.$autores["person"]["name"].'';
                                            }
                                            
                                        }
                                        $array_aut = implode("; ",$authors_array);
                                        unset($authors_array);
                                        echo '<p class="text-muted"><b>Autores:</b> '.''. $array_aut.'</p>';
                                    ?>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['about'])) : ?>
                                    <?php 
                                        foreach ($r["_source"]['about'] as $assunto) {
                                            $assunto_array[]=''.$assunto.'';
                                        }
                                        $array_assunto = implode("; ",$assunto_array);
                                        unset($assunto_array);
                                        echo '<p class="text-muted"><b>Assuntos:</b> '.''. $array_assunto.'</p>';
                                    ?>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['description'])) : ?>
                                    <p class="text-muted"><b>Resumo:</b> <?php echo $r["_source"]['description'] ?></p>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['publisher']['organization']['name'])) : ?>
                                    <p class="text-muted"><b>Editora:</b> <?php echo $r["_source"]['publisher']['organization']['name'];?></p>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['ISBN'])) : ?>
                                    <p class="text-muted"><b>ISBN:</b> <?php echo $r["_source"]['ISBN'][0];?></p>
                                <?php endif; ?>                                                                                                 
                                
                                <?php if (!empty($r["_source"]['doi'])) : ?>
                                    <p class="text-muted"><b>DOI:</b> <a href="http://dx.doi.org/<?php echo $r["_source"]['doi'];?>" target="_blank"><?php echo $r["_source"]['doi'];?></a></p>
                                <?php endif; ?>

                                <?php if (!empty($r["_source"]['facebook']['facebook_total'])) : ?>
                                    
                                    <table class="table"><caption>Interações no Facebook</caption>        
                                        <thead>
                                            <tr>
                                                <th>Reactions</th>
                                                <th>Comentários</th>
                                                <th>Compartilhamentos</th>                        
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $r["_source"]['facebook']['reaction_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['comment_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['share_count'];?></td>
                                                <td><?php echo $r["_source"]['facebook']['facebook_total'];?></td>
                                            </tr>
                                        </tbody>   
                                    </table><br/>                        
                                <?php endif; ?>

                        <?php
                        //$record = new Record($cursor, $show_metrics);
                        //$record->completeRecordMetadata($t, $url_base, $csl_abnt);
                        ?>                   

                        </div>
                        <div class="col-4">  

                        <div class="uk-card uk-card-body">
                            <h5 class="uk-panel-title">Exportar registro bibliográfico</h5>
                            <ul class="uk-nav uk-margin-top uk-margin-bottom">
                                <hr>
                                <li>
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=ris" rel="noopener noreferrer nofollow">RIS (EndNote)</a>
                                </li>
                                <li class="uk-nav-divider">
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=bibtex" rel="noopener noreferrer nofollow">Bibtex</a>
                                </li>
                                <li class="uk-nav-divider">
                                    <a target="_blank" rel="noopener noreferrer" class="uk-button uk-button-primary" href="<?php echo $url_base; ?>/tools/export.php?search[]=(sysno.keyword%3A<?php echo $cursor["_id"];?>)&format=csvThesis" rel="noopener noreferrer nofollow">Tabela (TSV)</a>
                                </li>
                            </ul>                            
                        </div>

                        <!-- Other works of same authors - Start -->
                        <?php
                        if (isset($cursor["_source"]["authorUSP"])) {
                            foreach ($cursor["_source"]["authorUSP"] as $authorUSPArray) {
                                $authorUSPArrayCodpes[] = $authorUSPArray["codpes"];
                            }
                            $queryOtherWorks["query"]["bool"]["must"]["query_string"]["query"] = 'authorUSP.codpes:('.implode(" OR ", $authorUSPArrayCodpes).')';
                            $queryOtherWorks["query"]["bool"]["must_not"]["term"]["name.keyword"] = $cursor["_source"]["name"];
                            $resultOtherWorks = Elasticsearch::search(["_id","name"], 10, $queryOtherWorks);
                            echo '<div class="uk-alert-primary" uk-alert>';
                            echo '<h5>Últimas obras dos mesmos autores vinculados com a USP cadastradas na BDPI:</h5><ul class="list-group list-group-flush">';
                            foreach ($resultOtherWorks["hits"]["hits"] as $othersTitles) {
                                //print_r($othersTitles);
                                echo '<li class="list-group-item"><a href="'.$url_base.'/item/'.$othersTitles["_id"].'" target="_blank">'.$othersTitles["_source"]["name"].'</a></li>';
                            }
                            echo '</ul></div>';
                        }
                        ?>
                        <!-- Other works of same authors - End -->

                    </div>
                </div>
                <hr class="uk-grid-divider">
            <?php require 'inc/footer.php'; ?>                   
            
            </div>
                       

</body>
</html>