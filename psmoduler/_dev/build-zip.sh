filename="psmoduler.zip"
rm -rf ../../temp
mkdir -p ../../temp
cd ..
composer install
rsync -r --exclude '_dev' --exclude 'tests' --exclude '.gitignore' --exclude 'docker-compose.yml' \
--exclude 'phpunit.xml' --exclude 'psmoduler.zip' --exclude 'views/manifest.json' ./ ../temp
cd ..
cd temp
zip -r ../psmoduler/$filename . *
cd ..
rm -rf temp


