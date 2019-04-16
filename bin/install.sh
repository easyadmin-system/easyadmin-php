#!/bin/bash
#
# Installation from subversion
#


# Get source directory
source _get_source_dir.sh


# Check for previous installation
INSTALL_LOG_FILE=$SOURCE/log/install.log
if [ -f $INSTALL_LOG_FILE ]; then
    echo "Error: Installation log file '${INSTALL_LOG_FILE}' already exists, exitting."
    exit 1
fi


# Basic variables
HOST=$(cat /etc/hostname)
read -p "Project name (easyadmin-php): " PROJECT
PROJECT=${PROJECT:-easyadmin-php}
read -p "Domain (easyadmin.test): " DOMAIN
DOMAIN=${DOMAIN:-easyadmin.test}
read -p "MySQL host (default: localhost): " DB_HOST
DB_HOST=${DB_HOST:-localhost}
read -p "MySQL username (default: root): " DB_USERNAME
DB_USERNAME=${DB_USERNAME:-root}
read -p "MySQL password (default: aaa): " DB_PASSWORD
DB_PASSWORD=${DB_PASSWORD:-aaa}
read -p "MySQL database name (default: test): " DB_DATABASE
DB_DATABASE=${DB_DATABASE:-test}
read -p "MySQL tables prefix (default: eas): " DB_TABLE_PREFIX
DB_TABLE_PREFIX=${DB_TABLE_PREFIX:-eas}

while true; do
    read -p "Do you want to import data from repository to DB? [Y/n] " yn
    case $yn in
        [Yy]* ) DB_IMPORT=1; break;;
        [Nn]* ) DB_IMPORT=0; break;;
        * ) echo "Please answer yes or no.";;
    esac
done


# Config files
CONFIG_FILES=('apache2.dev.conf' 'application.config.dev.php' 'templates.config.dev.php')


# TARGET DIR
TARGET=/home/$USER/www/$PROJECT


# Check for previous installation
echo " → Checking for previous installation..."
if [ -d $TARGET ]; then
    echo "Error: '${TARGET}' already exists, exitting."
    exit 1
fi


# Remember install input values
echo " → Saving config file..."
mkdir -p $SOURCE/log
touch $INSTALL_LOG_FILE
chmod +x $INSTALL_LOG_FILE
echo "#!/bin/bash" > $INSTALL_LOG_FILE
echo "PROJECT=${PROJECT}" >> $INSTALL_LOG_FILE
echo "DOMAIN=${DOMAIN}" >> $INSTALL_LOG_FILE
echo "HOST=${HOST}" >> $INSTALL_LOG_FILE
echo "DB_HOST=${DB_HOST}" >> $INSTALL_LOG_FILE
echo "DB_USERNAME=${DB_USERNAME}" >> $INSTALL_LOG_FILE
echo "DB_PASSWORD=${DB_PASSWORD}" >> $INSTALL_LOG_FILE
echo "DB_DATABASE=${DB_DATABASE}" >> $INSTALL_LOG_FILE
echo "DB_TABLE_PREFIX=${DB_TABLE_PREFIX}" >> $INSTALL_LOG_FILE
echo "TARGET=${TARGET}" >> $INSTALL_LOG_FILE


# Other directories
DB_DIR="$SOURCE/db/mysql/install"
TEMP_DIR="$SOURCE/temp"


# Directories
echo " → Preparing temporary directories..."
if [ $DB_IMPORT == 1 ]; then
    mkdir -p $TEMP_DIR
fi


# MySQL data files
if [ $DB_IMPORT == 1 ]; then
    echo " → Preparing database import files..."
    MYSQL_FILES="$DB_DIR/structure.sql $DB_DIR/data.sql"
    for DBF in $MYSQL_FILES; do
        sed -e "s,__DOMAIN__,$DOMAIN,g" \
            $DBF > $TEMP_DIR/$(basename $DBF)
    done

    # Insert MySQL data
    echo " → Importing database files..."
    mysql -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < $TEMP_DIR/structure.sql
    mysql -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < $TEMP_DIR/data.sql
fi


# Directories
echo " → Preparing target directories..."
mkdir -p $TARGET
mkdir $TARGET/conf
mkdir $TARGET/log


# Logs
echo " → Preparing log files..."
DEBUG_LOG_FILE="$TARGET/log/debug.log"
touch $DEBUG_LOG_FILE


# Symlinks
echo " → Installing from ${SOURCE} to ${TARGET}..."
ln -s $SOURCE/wwwroot $TARGET/wwwroot


# Permissions for files
echo " → Checking file permissions..."
chmod 0777 $DEBUG_LOG_FILE


# Config files
echo " → Copying config files..."
CONFIG_PATHS=()
for FILENAME in "${CONFIG_FILES[@]}"; do
    CONFIG_PATHS+="$SOURCE/conf/$FILENAME "
done

for CFG in $CONFIG_PATHS; do
    sed -e "s,__DOMAIN__,$DOMAIN,g" \
        -e "s,__TARGET__,$TARGET,g" \
        -e "s,__SOURCE__,$SOURCE,g" \
        -e "s,__DB_HOST__,$DB_HOST,g" \
        -e "s,__DB_USERNAME__,$DB_USERNAME,g" \
        -e "s,__DB_PASSWORD__,$DB_PASSWORD,g" \
        -e "s,__DB_DATABASE__,$DB_DATABASE,g" \
        -e "s,__DB_TABLE_PREFIX__,$DB_TABLE_PREFIX,g" \
        $CFG > $TARGET/conf/$(basename $CFG)
done


# Virtual host
echo " → Creating virtualhost in user home directory..."
ln -s $TARGET/conf/apache2.dev.conf /home/$USER/vhosts/apache2/$PROJECT.conf


# Clean temporary data
echo " → Cleaning temporary files..."
rm -rf $TEMP_DIR


# Finish
echo "Installation complete."
