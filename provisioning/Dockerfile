# Gebruik een officiële Python-image als basis (Python 3.11)
FROM python:3.11

# Stel de werkdirectory in op /app binnen de container
WORKDIR /app

# Kopieer het requirements-bestand met Python-afhankelijkheden naar de container
COPY requirements.txt .

# Kopieer de inhoud van de map 'src' naar de werkdirectory in de container
COPY src/* /app

# Installeer Python dependencies en aanvullende systeempakketten
# - Installeer Python-pakketten
# - Installeer cron, tijdzone- en taalinstellingen
# - Stel de tijdzone in op Amsterdam
# - Herconfigureer de tijdzone zonder interactie
# - Voeg Nederlandse taalinstelling toe
# - Genereer de gewenste locale
# - Stel locale-variabelen in
# - Maak alle .sh-scripts uitvoerbaar
# - Maak een logmap aan

RUN pip install -r requirements.txt \
    && apt update && apt install -y cron tzdata locales \
    && ln -fs /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo "nl_NL.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=nl_NL.UTF-8 LC_ALL=nl_NL.UTF-8 \
    && chmod a+x /app/*.sh \
    && mkdir /app/logs

# Stel de omgevingsvariabelen in voor taal en locale
ENV LANG=nl_NL.UTF-8
ENV LC_ALL=nl_NL.UTF-8

# Kopieer het crontab-bestand naar het juiste systeempad
COPY crontab /etc/cron.d/run-sync

# Stel juiste rechten in voor de cronjob en activeer deze
# - Correcte permissies zodat cron het accepteert
# - Installeer de cronjob
# - Start cron-service (voor testen tijdens build)
# - Voorzie het logbestand van cron

RUN chmod 0644 /etc/cron.d/run-sync \
    && crontab /etc/cron.d/run-sync \
    && service cron start \
    && touch /var/log/cron.log

# Start de cron-service en blijf logbestand streamen zodat de container blijft draaien
CMD cron && tail -f /var/log/cron.log