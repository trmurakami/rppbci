#!/bin/bash
ontem=`date -d "-$1 day" --iso-8601`
hoje=`date --iso-8601`


# AtoZ: novas práticas em informação e conhecimento
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","AtoZ: novas praticas em informacao e conhecimento")' --fix 'set_array("qualis2014","B5")' --url http://revistas.ufpr.br/atoz/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# BIBLOS
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Biblos")' --fix 'set_array("qualis2014","B3")' --url http://www.seer.furg.br/biblos/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# BIBLIONLINE
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Biblionline")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/biblio/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Bibliotecas Universitárias: Pesquisas, experiências e perspectivas
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Bibliotecas Universitarias: Pesquisas, experiencias e perspectivas")' --fix 'set_array("qualis2014","Nao possui")' --url https://seer.ufmg.br/index.php/revistarbu/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Brazilian Journal of Information Science: Research Trends - BJIS
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Brazilian Journal of Information Science")' --fix 'set_array("qualis2014","B1")' --url http://www2.marilia.unesp.br/revistas/index.php/bjis/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Cadernos de Informação Jurídica (Cajur)
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Cadernos de Informacao Juridica")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.cajur.com.br/index.php/cajur/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2


# Ciência da Informação em Revista - CIR
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Ciencia da Informacao em Revista")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.seer.ufal.br/index.php/cir/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Comunicação & Informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Comunicacao e informacao")' --fix 'set_array("qualis2014","B2")' --url http://revistas.ufg.br/ci/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# CRB-8 Digital
#catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","crb8digital")' --fix 'set_array("qualis2014","B5")' --url http://revista.crb8.org.br/index.php/crb8digital/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose
#sleep 2

# Em Questão
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","EmQuestao")' --fix 'set_array("qualis2014","B1")' --url http://www.seer.ufrgs.br/index.php/EmQuestao/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Encontros Bibli: revista eletrônica de biblioteconomia e ciência da informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Encontros Bibli")' --fix 'set_array("qualis2014","B1")' --url https://periodicos.ufsc.br/index.php/eb/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Folha de Rosto
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Folha de Rosto")' --fix 'set_array("qualis2014","Nao possui")' --url http://periodicos.ufca.edu.br/ojs/index.php/folhaderosto/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informação e Informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informacao e Informacao")' --fix 'set_array("qualis2014","B1")' --url http://www.uel.br/revistas/uel/index.php/informacao/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informação e Sociedade: Estudos
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informacao e Sociedade: Estudos")' --fix 'set_array("qualis2014","A1")' --url http://www.ies.ufpb.br/ojs2/index.php/ies/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informação@Profissões - INFOPROF
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informacao@Profissoes")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.uel.br/revistas/uel/index.php/infoprof/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informação e Tecnologia - ITEC
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informacao e Tecnologia - ITEC")' --fix 'set_array("qualis2014","Nao possui")' --url http://periodicos.ufpb.br/ojs/index.php/itec/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informação em pauta - IP
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informacao em pauta")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.periodicos.ufc.br/index.php/informacaoempauta/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Informe: Estudos de Biblioteconomia e Gestão da Informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Informe")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.repositorios.ufpe.br/revistas/index.php/INF/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Intexto
#catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Intexto")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.seer.ufrgs.br/intexto/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
#sleep 2

# IRIS - Revista de Informação, Memória e Tecnologia
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","IRIS")' --fix 'set_array("qualis2014","B3")' --url http://www.repositorios.ufpe.br/revistas/index.php/IRIS/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Liinc em Revista - Liinc
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Liinc em Revista")' --fix 'set_array("qualis2014","B1")' --url http://liinc.revista.ibict.br/index.php/liinc/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Logeion - Filosofia da informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Logeion")' --fix 'set_array("qualis2014","Nao possui")' --url http://revista.ibict.br/fiinf/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Multiplos olhares em Ciência da Informação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Multiplos olhares em Ciencia da Informacao")' --fix 'set_array("qualis2014","Nao possui")' --url http://portaldeperiodicos.eci.ufmg.br/index.php/moci/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Perspectivas em Ciência da Informação - PCI
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Perspectivas em Ciencia da Informacao")' --fix 'set_array("qualis2014","A1")' --url http://portaldeperiodicos.eci.ufmg.br/index.php/pci/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Perspectivas em Gestão e Conhecimento - PGC
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Perspectivas em Gestao e Conhecimento")' --fix 'set_array("qualis2014","Nao possui")' --url http://periodicos.ufpb.br/ojs2/index.php/pgc/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Pesquisa Brasileira em Ciência da Informação e Biblioteconomia - PBCIB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PBCIB")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/pbcib/oai --set pbcib:PA --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Pesquisa Brasileira em Ciência da Informação e Biblioteconomia - PBCIB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PBCIB")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/pbcib/oai --set pbcib:AR --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Pesquisa Brasileira em Ciência da Informação e Biblioteconomia - PBCIB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PBCIB")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/pbcib/oai --set pbcib:M --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Pesquisa Brasileira em Ciência da Informação e Biblioteconomia - PBCIB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PBCIB")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/pbcib/oai --set pbcib:ED --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Pesquisa Brasileira em Ciência da Informação e Biblioteconomia - PBCIB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PBCIB")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.ufpb.br/ojs2/index.php/pbcib/oai --set pbcib:APT --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# PontodeAcesso
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","PontodeAcesso")' --fix 'set_array("qualis2014","B1")' --url http://www.portalseer.ufba.br/index.php/revistaici/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

#RECIIS
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","RECIIS")' --fix 'set_array("qualis2014","B1")' --url http://www.reciis.icict.fiocruz.br/index.php/reciis/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

#Revista ACB - RACB
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista ACB")' --fix 'set_array("qualis2014","B2")' --url http://revista.acbsc.org.br/racb/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Revista Brasileira de Biblioteconomia e Documentação - RBBD
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Brasileira de Biblioteconomia e Documentacao")' --fix 'set_array("qualis2014","B1")' --url http://rbbd.febab.org.br/rbbd/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Revista Brasileira de Educação em Ciência da Informação - Rebecin
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Brasileira de Educacao em Ciencia da Informacao")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.abecin.org.br/revista/index.php/rebecin/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

#Revista Ciência da Informação - CIInf
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Ciencia da Informacao")' --fix 'set_array("qualis2014","B1")' --url http://revista.ibict.br/ciinf/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

#Revista Conhecimento em Ação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Conhecimento em Acao")' --fix 'set_array("qualis2014","Nao possui")' --url https://revistas.ufrj.br/index.php/rca/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Revista Digital de Biblioteconomia e Ciência da Informação - RDBCI
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Digital de Biblioteconomia e Ciencia da Informacao")' --fix 'set_array("qualis2014","B1")' --url http://periodicos.sbu.unicamp.br/ojs/index.php/rdbci/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Revista Ibero-Americana de Ciência da Informação - RICI
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Revista Ibero Americana de Ciencia da Informacao")' --fix 'set_array("qualis2014","Nao possui")' --url http://periodicos.unb.br/index.php/rici/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Tendências da Pesquisa Brasileira em Ciência da Informação - TPBCI
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Tendencias da Pesquisa Brasileira em Ciencia da Informacao")' --fix 'set_array("qualis2014","B1")' --url http://inseer.ibict.br/ancib/index.php/tpbci/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Transinformação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Transinformacao")' --fix 'set_array("qualis2014","A1")' --url http://periodicos.puc-campinas.edu.br/seer/index.php/transinfo/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

#InCID: Revista de Ciência da Informação e Documentação
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","InCID")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.revistas.usp.br/incid/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

# Biblioteca Escolar em Revista - berev
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Biblioteca Escolar em Revista")' --fix 'set_array("qualis2014","B3")' --url http://www.revistas.usp.br/berev/oai --metadataPrefix oai_dc --from $ontem --until $hoje to rppbci --bag journals --verbose
sleep 2

curl -XPOST 'http://localhost:9200/rppbci/journals/_delete_by_query' -d '{ "query": { "term": { "status": "deleted" } } }'
                

