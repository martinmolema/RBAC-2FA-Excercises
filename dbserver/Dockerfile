####################################################################################################################
# Docker file for the IAM Database
####################################################################################################################

# Use Linux and mariadb image
FROM mariadb:10.10-jammy

# Make sure the timezone is set to Europe/Amsterdam
RUN ln -sf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

# Make the working directory
RUN mkdir /app

# Init and seed the database using an inital SQL script
# https://hub.docker.com/_/mariadb see "Initializing the database contents"
COPY --chown=root:root  CreateDatabase-and-seed.sql /docker-entrypoint-initdb.d
