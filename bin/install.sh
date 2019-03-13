#!/bin/bash
#
# Installation from subversion
#

# Basic variables
HOST=$(cat /etc/hostname)
read -p "Project name: " PROJECT
read -p "Your e-mail: " EMAIL
TARGET=/home/$USER/www/$PROJECT
SOURCE=$(readlink -f $(dirname $(readlink -f ${BASH_SOURCE[0]}))"/..")

# Check for previous installation
if [ -d $TARGET ]; then
	echo "Error: '${TARGET}' already exists, exitting."
	exit 1
fi

# Directories
echo "Preparing directories..."
mkdir -p $TARGET
mkdir $TARGET/conf
mkdir $TARGET/log

# Logs
touch $TARGET/log/debug.log

# Symlinks
echo "Going to install from ${SOURCE} to ${TARGET}..."
ln -s $SOURCE/wwwroot $TARGET/wwwroot
ln -s $TARGET/log/debug.log $SOURCE/wwwroot/log/debug.log

# Permissions for files
chmod 0777 $SOURCE/wwwroot/log/debug.log

# Config files
echo "Copying config files..."
CONFIG_FILES="$SOURCE/conf/virtualhost.dev.conf"
for CFG in $CONFIG_FILES; do
	sed -e "s,__PROJECT__,$PROJECT," \
		-e "s,__TARGET__,$TARGET," \
		-e "s,__HOST__,$HOST," \
		-e "s,__EMAIL__,$EMAIL," \
		$CFG > $TARGET/conf/$(basename $CFG)
done

# Virtual host
echo "Creating virtualhost..."
ln -s $TARGET/conf/virtualhost.dev.conf /home/$USER/vhosts/$PROJECT.conf

# Finish
echo "Installation complete."
