#!/usr/bin/python3

################################################################################################################################
# Author  : Martin Molema (martin@molema.org / martin.molema@nhlstenden.com)
# Goal    : Export all diagrams from a given Diagrams.net / DrawIO file. 
# Version : 1
#
# Requires:
# - Python: xml.etree.ElementTree (https://docs.python.org/3/library/xml.etree.elementtree.html)
# - Installed version of DrawIO (offline) See http://www.diagrams.net / https://github.com/jgraph/drawio-desktop
#
################################################################################################################################

# Example usage: draw-io-export.py --scale=2 --format=PNG --output-directory=images -t Diagrams.drawio

import collections
import subprocess
import xml.etree.ElementTree as ET
import getopt, sys
import os.path
import re

drawIoExecutable = "/mnt/ssd/Apps/drawio/drawio"

# First setup the options in a generic way
OptionInfo = collections.namedtuple('OptionInfo', 'short, long, hasParameter, parameterName, usage')

optionlist = [
    OptionInfo('h', 'help', False, '', 'display this help'),
    OptionInfo('b', 'basename', True, 'basename', 'the base of the filename to which the pagename is added'),
    OptionInfo('s', 'scale', True, 'scale', 'scale the image; value must be an number; 1=100%, 2=200% etc.'),
    OptionInfo('f', 'format', True, 'format', 'supported export types by drawIO. e.g. PNG or JPG'),
    OptionInfo('d', 'output-directory', True, 'path', 'the directory where exported files are placed'),
    OptionInfo('t', 'transparent', False, '', 'background of the exported image is made transparent'),
    OptionInfo('p', 'page', True, '*', 'the page to be exported. Supports regex. '),
]

# init vars
options = ""
long_options = []
usageText = ""

basename = ""
inputFullPath = ""
format = "PNG"
scale = "1"
directory = f".{os.path.sep}exportedPages"
transparent = False
pagesToExport = '.*'

# get the name of the executed script from the command line options (remove the preceding path if present)
helpCommandLine = os.path.basename(sys.argv[0]) + " "

# Now convert the list of options to an string that can be parsed by GetOpts; in the meanwhile construct the USAGE
# text.
for opt in optionlist:
    # destruct the tuple to separate parameters
    short, long, hasParameter, parameterName, usage = opt

    # create an option string for GetOpts; if options have a parameter a colon ':' is added
    options += short + (":" if hasParameter else "")

    # append to the array of long options; if options have a parameter a '=' sign is added
    longOption = long + ("=" if hasParameter else "")
    long_options.append(longOption)

    # construct the usage text
    usageText += "-" + short + " | --" + long + "\n     " + usage + "\n"

    # construct the usage command line parameter string
    helpCommandLine += "[ -" + short + " " + (parameterName if hasParameter else "")
    helpCommandLine += "| --" + long + " " + (parameterName if hasParameter else "")
    helpCommandLine += "] "

# the end of the command line is the filename
helpCommandLine += " filename"


# Function to show the usage of this script
def Usage():
    global usageText, helpCommandLine
    print("Usage:")
    print(helpCommandLine + "\n")
    print(usageText)


# Remove 1st argument from the list of command line arguments (this is the command itself)
argumentList = sys.argv[1:]

# Now start parsing the options
try:
    # Parsing argument
    arguments, values = getopt.getopt(argumentList, options, long_options)

    """ 
      the list of values contains the 'rest' of the parameters not preceded by an option. 
      there should be at least one filename mentioned otherwise there is nothing to process!
    """
    if (len(values) != 0):
        inputFullPath = values[0]

        # set the basename default to the same name as the filename without the extension
        filename = os.path.basename(inputFullPath)
        basename = os.path.splitext(filename)[0]

    else:
        print("No filename found")
        Usage()
        exit(1)

    # checking each argument
    for currentArgument, currentValue in arguments:
        # find the correct tuple so we can do easy comparison on the short option letter
        oneOption = list(filter(
            lambda x: "-" + x.short == currentArgument or
                      "--" + x.long == currentArgument, optionlist
        ))

        shortOptionLetter = oneOption[0].short

        if shortOptionLetter == 'h':
            Usage()
            exit(0)
        elif shortOptionLetter == "b":
            basename = currentValue
        elif shortOptionLetter == "s":
            scale = currentValue
        elif shortOptionLetter == "f":
            format = currentValue
        elif shortOptionLetter == "d":
            directory = currentValue
        elif shortOptionLetter == "t":
            transparent = True
        elif shortOptionLetter == "p":
            pagesToExport = currentValue

except getopt.error as err:
    # output error, and return with an error code
    print(str(err))
    exit(2)

# Check if the directory ends with a forward slash
if directory[-1:] != os.path.sep:
    directory = f"{directory}{os.path.sep}"

if directory[1:1] != os.path.sep:
    directory = f"{os.getcwd()}{os.path.sep}{directory}"

print(("Using input file %s") % (inputFullPath))
print(("Using output base filename %s") % (basename))
print(("Sending result files to directory %s") % (directory))

if not os.path.exists(directory):
    try:
        print(f"Destination path does not exist. Creating {directory}")
        os.makedirs(directory)
    except:
        print(f"Could not create path! {directory}")
        sys.exit(1)

root = ET.parse(inputFullPath).getroot()
pageNumber = 0
for diagram in root.findall('diagram'):

    # get the name-attribute of the diagram element
    pagename = diagram.get('name')
    print("Processing " + pagename)

    exportThisPage = re.search(pagesToExport, pagename)

    if exportThisPage:
        # construct a new filename (directory already ends with OS-path separator char
        newfilename = f"{directory}{basename} - {pagename}.png"

        # construct a command line.
        # setup the commandline as an array of parameters; options that have parameters will be separated into two parts
        # escaping spaces for filenames is not necessary
        commandline = []
        commandline.append(drawIoExecutable)
        commandline.append(f"-x")
        commandline.append(f"-p")
        commandline.append(f"{pageNumber}")
        commandline.append(f"-o")
        commandline.append(f"{newfilename}")
        commandline.append(f"-f")
        commandline.append(f"{format}")
        commandline.append(f"-s")
        commandline.append(f"{scale}")
        if transparent:
            commandline.append(f"-t")

        # add the filename as the last parameter
        commandline.append(inputFullPath)

        try:
            print(f"Using working directory {os.getcwd()}")
            result = subprocess.run(commandline, stderr=subprocess.PIPE, stdout=subprocess.PIPE)
            print(result.stdout.decode('utf-8'))
        except:
            print("Error running command line")
            print(result.stderr.decode('utf-8'))
            sys.exit(1)

        pageNumber += 1
    else:
        print('- Skipping due to page filter')
