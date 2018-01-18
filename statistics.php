<html>
    <head>
        <?php 
            require 'inc/config.php'; 
            require 'inc/functions.php';
            require 'inc/meta-header.php';
        ?>
        <title>Estatísticas</title>
    </head>    
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
            <?php require 'inc/navbar.php' ?>
            
            <h1>Estatísticas</h1>
            
            <h2>Facebook</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=metric&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:Coment%C3%A1rios,field:facebook.comment_count),schema:metric,type:sum),(enabled:!t,id:'2',params:(customLabel:Reactions,field:facebook.reaction_count),schema:metric,type:sum),(enabled:!t,id:'3',params:(customLabel:Compartilhamentos,field:facebook.share_count),schema:metric,type:sum),(enabled:!t,id:'4',params:(customLabel:'Total+de+intera%C3%A7%C3%B5es+no+Facebook',field:facebook.facebook_total),schema:metric,type:sum)),listeners:(),params:(fontSize:60,handleNoResults:!t),title:'New+Visualization',type:metric))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>

            <h2>Facebook de divulgação científica</h2>

            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=metric&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:Coment%C3%A1rios,field:facebook_doi.comment_count),schema:metric,type:sum),(enabled:!t,id:'2',params:(customLabel:Reactions,field:facebook_divulgacao.reaction_count),schema:metric,type:sum),(enabled:!t,id:'3',params:(customLabel:Compartilhamentos,field:facebook_divulgacao.share_count),schema:metric,type:sum),(enabled:!t,id:'4',params:(customLabel:Total,field:facebook_divulgacao.facebook_total),schema:metric,type:sum)),listeners:(),params:(fontSize:60,handleNoResults:!t),title:'New+Visualization',type:metric))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Facebook de divulgação científica por artigo e periódico</h2>

            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook_divulgacao.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:source.keyword,order:desc,orderBy:'1',size:13),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:titulo.keyword,order:desc,orderBy:'1',size:40),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,categoryAxes:!((id:CategoryAxis-1,labels:(show:!t,truncate:100),position:bottom,scale:(type:linear),show:!t,style:(),title:(text:'source.keyword:+Descending'),type:category)),defaultYExtents:!f,drawLinesBetweenPoints:!t,grid:(categoryLines:!f,style:(color:%23eee)),interpolate:linear,legendPosition:right,radiusRatio:9,scale:linear,seriesParams:!((data:(id:'1',label:'Sum+of+facebook_divulgacao.facebook_total'),drawLinesBetweenPoints:!t,mode:stacked,show:true,showCircles:!t,type:histogram,valueAxis:ValueAxis-1)),setYExtents:!f,showCircles:!t,times:!(),valueAxes:!(('$$hashKey':'object:2190',id:ValueAxis-1,labels:(filter:!f,rotate:0,show:!t,truncate:100),name:LeftAxis-1,position:left,scale:(mode:normal,type:linear),show:!t,style:(),title:(text:'Sum+of+facebook_divulgacao.facebook_total'),type:value))),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
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
            
            <h2>Por tipo de interação no facebook</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'2',params:(customLabel:Coment%C3%A1rios,field:facebook.comment_count),schema:metric,type:sum),(enabled:!t,id:'3',params:(customLabel:comment_plugin,field:facebook.comment_plugin_count),schema:metric,type:sum),(enabled:!t,id:'4',params:(customLabel:Reactions,field:facebook.reaction_count),schema:metric,type:sum),(enabled:!t,id:'5',params:(customLabel:Compartilhamentos,field:facebook.share_count),schema:metric,type:sum)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Interações por posição de link</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'2',params:(customLabel:'Link+0',field:facebook.fb0),schema:metric,type:sum),(enabled:!t,id:'3',params:(customLabel:'Link+1',field:facebook.fb1),schema:metric,type:sum),(enabled:!t,id:'4',params:(customLabel:'Link+2',field:facebook.fb2),schema:metric,type:sum),(enabled:!t,id:'5',params:(customLabel:'Link+3',field:facebook.fb3),schema:metric,type:sum),(enabled:!t,id:'6',params:(customLabel:'Link+4',field:facebook.fb4),schema:metric,type:sum),(enabled:!t,id:'7',params:(customLabel:'Link+5',field:facebook.fb5),schema:metric,type:sum),(enabled:!t,id:'8',params:(customLabel:'Link+6',field:facebook.fb6),schema:metric,type:sum),(enabled:!t,id:'9',params:(customLabel:'Link+7',field:facebook.fb7),schema:metric,type:sum)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:grouped,orderBucketsBySum:!f,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Interações por Qualis 2015</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:'Intera%C3%A7%C3%B5es+no+Facebook',field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:qualis2015.keyword,order:desc,orderBy:'1',size:50),schema:segment,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
                                  
            <h2>Quantidade de artigos por autor</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=table&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(vis:(params:(sort:(columnIndex:!n,direction:!n)))),vis:(aggs:!((enabled:!t,id:'1',params:(customLabel:'Quantidade+de+artigos'),schema:metric,type:count),(enabled:!t,id:'2',params:(customLabel:Autores,field:autores.nomeCompletoDoAutor.keyword,order:desc,orderBy:'1',size:50),schema:bucket,type:terms)),listeners:(),params:(perPage:10,showMeticsAtAllLevels:!f,showPartialRows:!f,showTotal:!f,sort:(columnIndex:!n,direction:!n),totalFunc:sum),title:'New+Visualization',type:table))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Altmetrics por Instituição e Periódico</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:autores.afiliacao.keyword,order:desc,orderBy:'1',size:30),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Altmetrics por Palavra-Chave e por Ano</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(('$$hashKey':'object:1742','$state':(store:appState),meta:(alias:!n,apply:!t,disabled:!f,index:rppbci3,key:palavras_chave.keyword,negate:!t,value:''),query:(match:(palavras_chave.keyword:(query:'',type:phrase))))),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(field:facebook.facebook_total),schema:metric,type:sum),(enabled:!t,id:'2',params:(field:palavras_chave.keyword,order:desc,orderBy:'1',size:50),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:ano.keyword,order:desc,orderBy:'1',size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Nota no altmetric.com por revista</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(),schema:metric,type:count),(enabled:!t,id:'2',params:(field:altmetric_com.score,order:desc,orderBy:'1',size:100),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Leitores no Mendeley (altmetric.com)</h2>
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=<?php echo $index; ?>&_g=()&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((enabled:!t,id:'1',params:(),schema:metric,type:count),(enabled:!t,id:'2',params:(field:altmetric_com.readers.mendeley,order:desc,orderBy:'1',size:100),schema:segment,type:terms),(enabled:!t,id:'3',params:(field:source.keyword,order:desc,orderBy:'1',size:200),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,legendPosition:right,mode:stacked,scale:linear,setYExtents:!f,times:!()),title:'New+Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            
            <?php require 'inc/offcanvas.php' ?>
        </div>    
    </body>
</html>