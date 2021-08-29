FROM postgres:13.4

ENV ACCEPT_EULA=Y
ENV POSTGRES_DB=postgres
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    postgis \
    postgresql-13-postgis-3;
COPY init.sql /docker-entrypoint-initdb.d/
