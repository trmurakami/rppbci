<html>
    <head>
        <?php 
            include('inc/config.php'); 
            include('inc/functions.php');
            include('inc/meta-header.php');
        ?>
        <title>Estatísticas do RPPBCI</title>
    </head>    
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
            <?php include('inc/navbar.php')?>
            
            <h1>Estatísticas do RPPBCI</h1>
            
            <h2>Publicações por periódico e por ano de publicação</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(),schema:metric,type:count),(enabled:!t,id:'2',params:(field:ano.keyword,order:asc,orderBy:_term,size:200),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Altmetrics por ano de publicação e por periódico</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:ano.keyword,order:asc,orderBy:_term,size:500),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:500),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="500" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <!--
            <h2>Altmetrics por Qualis 2014 e por periódico</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-15m,mode:quick,to:now))&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((id:'1',params:(field:facebook.total),schema:metric,type:sum),(id:'2',params:(field:qualis2014,order:asc,orderBy:_term,size:5500),schema:segment,type:terms),(id:'3',params:(field:journalci_title,order:desc,orderBy:'1',size:5000),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,mode:stacked,scale:linear,setYExtents:!f,shareYAxis:!t,times:!(),yAxis:()),title:'New%20Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            -->

            <h2>Top 20 autores/perióricos com mais altmetrics</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:autores.nomeCompletoDoAutor.keyword,order:desc,orderBy:'1',size:20),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:50),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>10 Artigos por altmetrics e ano</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=table&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(vis:(params:(sort:(columnIndex:!n,direction:!n)))),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:'',field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(customLabel:Ano,field:ano.keyword,order:desc,orderBy:_term,row:!t,size:5),schema:split,type:terms),(enabled:!t,id:'3',params:(customLabel:T%C3%ADtulo,field:titulo.keyword,order:desc,orderBy:'1',size:10),schema:bucket,type:terms)),listeners:(),params:(perPage:10,showMeticsAtAllLevels:!f,showPartialRows:!f,showTotal:!f,sort:(columnIndex:!n,direction:!n),totalFunc:sum),title:'New+Visualization',type:table))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Palavras-chave por ano</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(('$$hashKey':'object:2017','$state':(store:appState),meta:(alias:!n,disabled:!f,index:rppbci2,key:palavras_chave.keyword,negate:!t,value:''),query:(match:(palavras_chave.keyword:(query:'',type:phrase))))),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(),schema:metric,type:count),(enabled:!t,id:'2',params:(field:palavras_chave.keyword,order:desc,orderBy:'1',size:30),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:ano.keyword,order:desc,orderBy:_term,size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Artigos com altmetrics por revista</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(),schema:metric,type:count),(enabled:!t,id:'2',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:facebook.facebook_total,ranges:!((from:0,to:1),(from:1,to:10000))),schema:group,type:range)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Quantidade de artigos por autor</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=table&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(vis:(params:(sort:(columnIndex:!n,direction:!n)))),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:'Quantidade+de+artigos'),schema:metric,type:count),(enabled:!t,id:'2',params:(customLabel:Autores,field:autores.nomeCompletoDoAutor.keyword,order:desc,orderBy:'1',size:50),schema:bucket,type:terms)),listeners:(),params:(perPage:10,showMeticsAtAllLevels:!f,showPartialRows:!f,showTotal:!f,sort:(columnIndex:!n,direction:!n),totalFunc:sum),title:'New+Visualization',type:table))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            
            <?php include('inc/offcanvas.php')?>
        </div>    
    </body>
</html>