#!/bin/bash

# AtoZ: novas práticas em informação e conhecimento
catmandu import OAI --fix fixes_oai.txt --fix 'set_array("journalci_title","AtoZ: novas praticas em informacao e conhecimento")' --fix 'set_array("qualis2014","B5")' --url http://ojs.c3sl.ufpr.br/ojs2/index.php/atoz/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose

catmandu import OAI --fix fixes_oai.txt --url http://ojs.c3sl.ufpr.br/ojs2/index.php/atoz/oai --metadataPrefix oai_dc to rppbci --bag journals --verbose