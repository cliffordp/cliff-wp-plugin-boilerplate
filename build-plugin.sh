#!/bin/bash
printf "Plugin name (written with spaces): "
read PLUGIN_NAME

if [ -z "$PLUGIN_NAME" ]; then
      echo "Plugin name cannot be empty"
      exit 1
fi

PLUGIN_NAME_UNDERSCORE=$( tr '[A-Z]' '[a-z]' <<< ${PLUGIN_NAME// /_})
PLUGIN_NAME_DASHES=$( tr '[A-Z]' '[a-z]' <<< ${PLUGIN_NAME// /-})
PLUGIN_NAME_CAMELCASE=$( perl -pe 's/[-_](.)/\u$1/g' <<< ${PLUGIN_NAME// /_})

printf "Author name (optional): "
read AUTHOR_NAME

printf "Author email (optional): "
read AUTHOR_EMAIL

printf "Author URL (optional): "
read AUTHOR_URL

printf "WP username (optional): "
read WP_USERNAME

printf "Composer vendor name (optional): "
read VENDOR_NAME

VENDOR_NAME=$( tr '[A-Z]' '[a-z]' <<< ${VENDOR_NAME// /-})

printf "Destination folder (default current directory): "
read FOLDER

if [ -z "$FOLDER" ]; then
      FOLDER="."
fi

printf "Initialise new git repo (y/n): "
read NEWREPO

DEFAULT_PLUGIN_NAME="WordPress Plugin Boilerplate"
DEFAULT_PLUGIN_NAME_DASHES="cliff-wp-plugin-boilerplate"
DEFAULT_PLUGIN_NAME_UNDERSCORE="cliff_wp_plugin_boilerplate"
DEFAULT_PLUGIN_CLASS="WpPluginName"
DEFAULT_WP_USER="cliffpaulick"

DIRECTORY=${FOLDER}/${PLUGIN_NAME_DASHES}

echo "Clone repo..."

rm -rf $DIRECTORY
git clone https://github.com/cliffordp/cliff-wp-plugin-boilerplate.git $DIRECTORY

cd $DIRECTORY

mv "$DEFAULT_PLUGIN_NAME_DASHES.php" "$PLUGIN_NAME_DASHES.php"

echo "Removing git files..."

rm -rf .git

echo "Cleaning up files..."

rm README.md
rm build-plugin.sh
rm .github/FUNDING.yml
rm .all-contributorsrc

echo "Updating plugin files..."

searchReplaceCmd="find . -type f | xargs sed -i ";

uname=$(uname);
case "$uname" in
    (*Darwin*)
      LC_CTYPE=C
      LANG=C
      searchReplaceCmd="find . -type f | xargs sed -i '' ";
    ;;
esac;

eval "$searchReplaceCmd 's/$DEFAULT_PLUGIN_NAME_DASHES/$PLUGIN_NAME_DASHES/g'"

eval "$searchReplaceCmd 's/$DEFAULT_PLUGIN_NAME_UNDERSCORE/$PLUGIN_NAME_UNDERSCORE/g'"

eval "$searchReplaceCmd 's/$DEFAULT_PLUGIN_NAME/$PLUGIN_NAME/g'"

eval "$searchReplaceCmd 's/$DEFAULT_PLUGIN_CLASS/$PLUGIN_NAME_CAMELCASE/g'"

mv languages/$DEFAULT_PLUGIN_NAME_DASHES.pot languages/$PLUGIN_NAME_DASHES.pot

if [ -n "$AUTHOR_NAME" ]; then
  eval "$searchReplaceCmd 's/Your Name or Your Company/$AUTHOR_NAME/g'"
fi

if [ -n "$AUTHOR_EMAIL" ]; then
  eval "$searchReplaceCmd 's/your@email.address/$AUTHOR_EMAIL/g'"
fi

if [ -n "$AUTHOR_URL" ]; then
  DEFAULT_URL="https://www.example.com/"
  eval "$searchReplaceCmd 's/$DEFAULT_URL/$AUTHOR_URL/g'"
fi

if [ -n "$WP_USERNAME" ]; then
  eval "$searchReplaceCmd 's/$DEFAULT_WP_USER/$WP_USERNAME/g'"
fi

if [ -n "$VENDOR_NAME" ]; then
  eval "$searchReplaceCmd 's/yourname/$VENDOR_NAME/g'"
fi

echo "Installing packages..."

composer install
npm install
npm run build

if [ "$NEWREPO" == "y" ]; then
	echo "Initialising new git repo..."
	git init
	git add *
fi

echo "Complete!"
