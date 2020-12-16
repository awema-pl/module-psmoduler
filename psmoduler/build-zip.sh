filename="psmoduler.zip"
rm -rf ../temp
mkdir -p ../temp
composer install
rsync -r --exclude '*.log' \
--exclude '.idea' \
--exclude 'node_modules' \
--exclude 'composer.lock' \
--exclude 'yarn.lock' \
--exclude 'package-lock.json' \
--exclude 'package.json' \
--exclude 'webpack.config.js' \
--exclude 'docs' \
--exclude 'tests' \
--exclude '.gitignore' \
--exclude 'docker-compose.yml' \
--exclude 'phpunit.xml' \
--exclude 'psmoduler.zip' \
--exclude 'build-zip.sh' \
--exclude 'dist/manifest.json' \
--exclude 'resources/js' \
--exclude 'resources/css' \
--exclude 'resources/img' \
--exclude 'resources/fonts' \
--exclude 'resources/webfonts' \
./ ../temp
cd ..
cd temp
rm ./psmoduler/$filename
zip -r ../psmoduler/$filename . *
cd ..
rm -rf temp
