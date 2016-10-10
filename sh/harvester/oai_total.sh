#!/bin/bash

# AtoZ: novas práticas em informação e conhecimento
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","AtoZ: novas praticas em informacao e conhecimento")' --fix 'set_array("qualis2014","B5")' --url http://ojs.c3sl.ufpr.br/ojs2/index.php/atoz/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose
sleep 2

# BIBLOS
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Biblos")' --fix 'set_array("qualis2014","B3")' --url http://www.seer.furg.br/biblos/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose
sleep 2

# Ciência da Informação em Revista - CIR
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","Ciencia da Informacao em Revista")' --fix 'set_array("qualis2014","Nao possui")' --url http://www.seer.ufal.br/index.php/cir/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose
sleep 2

curl -XDELETE 'http://localhost:9200/rppbci/journals/_query' -d '{ "query": { "term": { "status": "deleted" } } }'
                

