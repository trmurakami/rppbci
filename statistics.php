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
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=rppbci&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-15m,mode:quick,to:now))&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((id:'1',params:(),schema:metric,type:count),(id:'2',params:(field:journalci_title,order:asc,orderBy:_term,size:5500),schema:segment,type:terms),(id:'3',params:(field:year,order:desc,orderBy:_term,size:120),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,mode:stacked,scale:linear,setYExtents:!f,shareYAxis:!t,times:!(),yAxis:()),title:'New%20Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Altmetrics por ano de publicação e por periódico</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=rppbci&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-15m,mode:quick,to:now))&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((id:'1',params:(field:facebook.total),schema:metric,type:sum),(id:'2',params:(field:year,order:asc,orderBy:_term,size:5500),schema:segment,type:terms),(id:'3',params:(field:journalci_title,order:desc,orderBy:'1',size:5000),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,mode:stacked,scale:linear,setYExtents:!f,shareYAxis:!t,times:!(),yAxis:()),title:'New%20Visualization',type:histogram))" height="500" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Altmetrics por Qualis 2014 e por periódico</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/app/kibana#/visualize/create?embed=true&type=histogram&indexPattern=rppbci&_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-15m,mode:quick,to:now))&_a=(filters:!(),linked:!f,query:(query_string:(analyze_wildcard:!t,query:'*')),uiState:(),vis:(aggs:!((id:'1',params:(field:facebook.total),schema:metric,type:sum),(id:'2',params:(field:qualis2014,order:asc,orderBy:_term,size:5500),schema:segment,type:terms),(id:'3',params:(field:journalci_title,order:desc,orderBy:'1',size:5000),schema:group,type:terms)),listeners:(),params:(addLegend:!t,addTimeMarker:!f,addTooltip:!t,defaultYExtents:!f,mode:stacked,scale:linear,setYExtents:!f,shareYAxis:!t,times:!(),yAxis:()),title:'New%20Visualization',type:histogram))" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <h2>Top 20 autores/perióricos com mais altmetrics</h2>
            
            <iframe src="http://bdpife2.sibi.usp.br:5601/goto/4e44983b9dadc30a36045125e622a008" height="600" width="1125" scrolling="no" frameborder="0" seamless="seamless"></iframe>
            
            <?php include('inc/offcanvas.php')?>
        </div>    
    </body>
</html>