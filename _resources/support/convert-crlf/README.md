# Hercompileren van programma

Hier zijn de stappen om het C++ programma te compileren met behulp van MSYS2 en de MinGW G++ compiler:

### Instructies voor het compileren van een C++ programma met MSYS2 en MinGW

1. **Installeer MSYS2**
    - Download de MSYS2 installer van de officiële website.
    - Volg de installatie-instructies op de website om MSYS2 te installeren.

2. **Update MSYS2**
    - Open de MSYS2 terminal (MSYS2 MSYS).
    - Voer de volgende commando's uit om de pakketlijsten en geïnstalleerde pakketten bij te werken:
      ```sh
      pacman -Syu
      ```
    - Sluit de terminal en open deze opnieuw om de updates te voltooien.
    - Voer nogmaals het update-commando uit om er zeker van te zijn dat alles up-to-date is:
      ```sh
      pacman -Syu
      ```

3. **Installeer MinGW-w64 en de G++ compiler**
    - Open de MSYS2 terminal (MSYS2 MSYS) en voer de volgende commando's uit om de MinGW-w64 toolchain en de G++ compiler te installeren:
      ```sh
      pacman -S mingw-w64-x86_64-toolchain
      pacman -S mingw-w64-x86_64-gcc
      ```

4. **Configureer de omgeving**
    - Open de MSYS2 terminal (MSYS2 MinGW 64-bit) om de juiste omgeving te gebruiken voor 64-bit compilatie.
    - Zorg ervoor dat de `mingw64` bin directory in je PATH staat. Dit zou automatisch moeten gebeuren bij het openen van de MSYS2 MinGW 64-bit terminal.

5. **Compileren van je C++ programma**
    - Navigeer naar de directory waar je C++ broncodebestand zich bevindt. Bijvoorbeeld, als je bestand `main.cpp` heet en zich in de map `C:\Projects\MyApp` bevindt, voer dan het volgende commando uit:
      ```sh
      cd /c/Projects/MyApp
      ```
    - Compileer je C++ programma met de G++ compiler:
      ```sh
      g++ -o myapp main.cpp
      ```
    - Dit zal een uitvoerbaar bestand genaamd `myapp.exe` genereren in dezelfde directory.

6. **Uitvoeren van je programma**
    - Voer het gegenereerde uitvoerbare bestand uit:
      ```sh
      ./myapp.exe
      ```

### Samenvatting van de commando's

Hier is een samenvatting van de belangrijkste commando's die je nodig hebt:

```sh
# Update MSYS2
pacman -Syu

# Installeer MinGW-w64 en G++
pacman -S mingw-w64-x86_64-toolchain
pacman -S mingw-w64-x86_64-gcc

# Navigeer naar je projectdirectory
cd /c/Projects/MyApp

# Compileer je C++ programma
g++ -o myapp main.cpp

# Voer je programma uit
./myapp.exe
```
