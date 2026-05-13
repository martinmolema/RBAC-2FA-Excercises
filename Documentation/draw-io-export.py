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
# --------------------------------- DRAW IO PARAMETERS ------------------------------------------
# Usage: draw.io [options] [input file/folder]
#
# Options:
#   -V, --version                                 output the version number
#   -c, --create                                  creates a new empty file if no file is passed
#   -k, --check                                   does not overwrite existing files
#   -x, --export                                  export the input file/folder based on the given options
#   -r, --recursive                               for a folder input, recursively convert all files in sub-folders also
#   -o, --output <output file/folder>             specify the output file/folder. If omitted, the input file name is used for output with the specified format as extension
#   -f, --format <format>                         if output file name extension is specified, this option is ignored (file type is determined from output extension, possible export formats are pdf, png, jpg, svg, vsdx, and xml) (default: "pdf")
#   -q, --quality <quality>                       output image quality for JPEG (default: 90)
#   -t, --transparent                             set transparent background for PNG
#   -e, --embed-diagram                           includes a copy of the diagram (for PNG, SVG and PDF formats only)
#   --embed-svg-images                            Embed Images in SVG file (for SVG format only)
#   -b, --border <border>                         sets the border width around the diagram (default: 0)
#   -s, --scale <scale>                           scales the diagram size
#   --width <width>                               fits the generated image/pdf into the specified width, preserves aspect ratio.
#   --height <height>                             fits the generated image/pdf into the specified height, preserves aspect ratio.
#   --crop                                        crops PDF to diagram size
#   -a, --all-pages                               export all pages (for PDF format only)
#   -p, --page-index <pageIndex>                  selects a specific page, if not specified and the format is an image, the first page is selected
#   -l, --layers <comma separated layer indexes>  selects which layers to export (applies to all pages), if not specified, all layers are selected
#   -g, --page-range <from>..<to>                 selects a page range (for PDF format only)
#   -u, --uncompressed                            Uncompressed XML output (for XML format only)
#   -z, --zoom <zoom>                             scales the application interface
#   --svg-theme <theme>                           Theme of the exported SVG image (dark, light [default]) (default: "light")
#   --svg-links-target <target>                   Target of links in the exported SVG image (auto [default], new-win, same-win) (default: "auto")
#   --enable-plugins                              Enable Plugins
#   -h, --help
#
################################################################################################################################

# Example usage: draw-io-export.py --scale=2 --format=PNG --output-directory=images -t Diagrams.drawio

import collections
import subprocess
import xml.etree.ElementTree as ET
import getopt, sys
import os.path
import traceback

drawIoExecutable="draw.io.exe"
# drawIOExecutablePath="C:\\PortableApps\\DrawioPortable"

# First setup the options in a generic way
OptionInfo = collections.namedtuple('OptionInfo', 'short, long, hasParameter, parameterName, usage')

optionlist = [
    OptionInfo('h', 'help', False, '', 'display this help'),
    OptionInfo('b', 'basename', True, 'basename', 'the base of the filename to which the pagename is added'),
    OptionInfo('s', 'scale', True, 'scale', 'scale the image; value must be an number; 1=100%, 2=200% etc.'),
    OptionInfo('f', 'format', True, 'format', 'supported export types by drawIO. e.g. PNG or JPG'),
    OptionInfo('d', 'output-directory', True, 'path', 'the directory where exported files are placed'),
    OptionInfo('t', 'transparent', False, '', 'background of the exported image is made transparent'),
]

# init vars
options = ""
long_options = []
usageText = ""

basename = ""
inputFullPath = ""
format = "PNG"
scale = "1"
directory = f".{os.path.sep}exportedImages"
transparent = False

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
pageNumber = 1
for diagram in root.findall('diagram'):

    # get the name-attribute of the diagram element
    pagename = diagram.get('name')
    print("Processing " + pagename)

    # construct a new filename (directory already ends with OS-path separator char
    newfilename = f"{directory}{basename} - {pagename}.png"

    # construct a command line. assume that drawio can be run using the path-variable;
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
        # result = subprocess.run(commandline, stderr=subprocess.PIPE, stdout=subprocess.PIPE, cwd=drawIOExecutablePath)
        result = subprocess.run(commandline, capture_output=True, text=True)
        print(result.stdout)
    except:
        print("Error running command line")
        print(traceback.format_exc())
        sys.exit(1)

    pageNumber += 1

