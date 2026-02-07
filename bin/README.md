# Exporteren van afbeeldingen

Deze repository gebruikt diagrammen gemaakt met [DrawIO](https://www.drawio.com/). Deze applicatie kan eenvoudig
meerdere diagrammen in één bestand opslaan. Dit voorkomt wildgroei aan bestanden.

Om deze diagrammen bruikbaar te maken in de Markdown bestanden moeten ze omgezet worden naar meer reguliere formaten
zoals `png` of `jpg`. Om in één keer alle tabbladen om te zetten naar dergelijke formaten is een klein Python script
gemaakt. Dit script is te vinden in deze map onder de naam `draw-io-export.py`.

Er is gekozen voor Python omdat dit een gangbare programmeertaal is op veel Linux systemen waar de bulk van deze 
repository mee gemaakt is. 

# Randvoorwaarden

Om dit script te kunnen gebruiken moet de DrawIO geinstalleerd zijn op de computer en uitvoerbaar zijn via de 
command-line. De locatie van DrawIO executable moet opgegeven worden in het script op regel 23:

```python
drawIoExecutable = "/mnt/ssd/Apps/drawio/drawio"
```