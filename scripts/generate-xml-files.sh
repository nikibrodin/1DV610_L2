#!/bin/bash

echo "Generating xml-files..."
echo "These files are included in .gitignore."
chmod +x generate-xml-files.sh
sudo touch ../database.xml
sudo chmod -R 777 ../database.xml
sudo echo $'<?xml version="1.0" encoding="UTF-8"?>\n<users></users>' >../database.xml
sudo touch ../reminders.xml
sudo chmod -R 777 ../reminders.xml
sudo echo $'<?xml version="1.0" encoding="UTF-8"?>\n<reminders></reminders>' >../reminders.xml