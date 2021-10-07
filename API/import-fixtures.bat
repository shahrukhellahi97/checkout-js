@echo off

docker cp fixtures.sql api_db_1:/fixtures.sql
docker-compose exec db sh -c "/opt/mssql-tools/bin/sqlcmd -S localhost -U SA -P \"abc1234!Password\" -Q < /fixtures.sql"

pause