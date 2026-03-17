bin/console doctrine:database:drop --force
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
#  app/bin/console doctrine:query:sql "$(<db/realisation/stages_insertInto_v2.sql)"