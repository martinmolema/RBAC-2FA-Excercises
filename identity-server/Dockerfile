####################################################################################################################
# Docker file for the LDAP Identity server Database
####################################################################################################################

# Use the latest Linux/Debian distro
FROM debian:latest

###
# Admin:  cn=admin,dc=NHLStenden,dc=com
# Password = test12345!
#
####

# Create working directory
RUN mkdir /app
WORKDIR /app

# Copy all shell scripts
COPY *.sh /app
RUN chmod a+x /app/*.sh

# Update repositories and install dependencies:
# - Locales
# - Timezones
# - Python 3 and pip
RUN apt update && apt install -y debconf-utils python3 python3-venv python3-pip tzdata locales \
    && ln -fs /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo "nl_NL.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=nl_NL.UTF-8 LC_ALL=nl_NL.UTF-8 \
    && /app/configure_ldap.sh \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y slapd ldap-utils

# Setup Python Virtual Environment
RUN python3 -m venv /app/venv \
    && /app/venv/bin/pip install --upgrade pip \
    && /app/venv/bin/pip install mysql-connector-python ldap3

# Copy all LDIF files to container
COPY ldif/Ldap-data-*.ldif /app
COPY ldif-base/*.base /app

# Create avatar files to container
RUN mkdir avatars
COPY avatars/*.jpeg avatars

# Copy all python scripts to container
COPY *.py /app


# Now start the LDAP daemon. it is not possible to execute commands after this
# So loading the LDIF-files must be done afterwards
#
RUN mkdir -p /var/run/slapd/
ENTRYPOINT slapd -h "ldap://0.0.0.0:389" -d 512
