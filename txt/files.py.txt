# This is a script to make txt file copies of all files in the current directory and any subdirectories, then output an HTML file that provides links to all the new text files.
# Created by Noah Scholfield 2018

import os
from subprocess import run

rootdir = "."

dirLen = len(rootdir)
txtFiles = []

# Returns the new path for the txt file
def txtDirectory(filePath):
    return filePath[:dirLen] + '/txt/' + filePath[(dirLen + 1):] + '.txt'

# Builds and writes the HTML file with links to all the txt files
def createTxtHTMLFile():
    output = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Text Files</title><style>body {font-family: sans-serif;}</style></head><body><h1>Text Files</h1><ul>'
    for file in txtFiles:
        output += '<li><a href="' + file + '">' + file[4:-4] + '</a></li>'
    output += '</ul></html>'
    file = open("text.html", "w")
    file.write(output)

# Finds all the files that need to be make into txt files
for subdir, dirs, files in os.walk(rootdir):
    # Ignore files already in txt directory
    if 'txt' in dirs:
        dirs.remove('txt')
    # Ignore .git files
    if '.git' in dirs:
        dirs.remove('.git')
    # Ignore vendor files
    if 'vendor' in dirs:
        dirs.remove('vendor')

    for file in files:
        # Skip dotfiles, images, and other unneeded files
        filesToSkip = ['env.php', 'text.html', 'README.md', 'package.json', 'composer.json', 'composer.lock']
        if(file[:1] == '.' or (file in filesToSkip) or file.find('.ico') != -1 or file.find('.png') != -1 or file.find('.jpg') != -1):
            continue
        filePath = os.path.join(subdir, file)
        newDirectory = txtDirectory(filePath)
        copy = 'ditto ' + filePath + ' ' + newDirectory
        run(copy, shell=True)

        txtFiles.append(newDirectory[(dirLen + 1):])

        print('\u001b[37;1m' + filePath[dirLen:] + ' copied to ' + newDirectory + '\033[0m')

print('\033[92m' + str(len(txtFiles)) + ' files copied to txt files' + '\033[0m')
createTxtHTMLFile()
