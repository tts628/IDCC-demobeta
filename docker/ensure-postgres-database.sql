\set ON_ERROR_STOP on
-- Idempotent on existing volumes; never rename or delete an existing database.
SELECT format('CREATE DATABASE %I OWNER %I', 'BatchDyeingCentral', 'idcc')
WHERE NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = 'BatchDyeingCentral')
\gexec
SELECT datname FROM pg_database WHERE datname = 'BatchDyeingCentral';
