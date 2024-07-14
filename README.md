# CAT CPNS

## Instalasi

```bash
# skip jika sudah ada source code (zip)
git clone [url]

cd [folder]

composer install
# or for production
# composer install --optimize-autoloader --no-dev

php artisan key:generate

cp .env.example .env

php artisan migrate

php artisan db:seed

php artisan optimize:clear

# for production
# php artisan config:cache
# php artisan event:cache
# php artisan route:cache
# php artisan view:cache


# yarn
# OR
npm install

# yarn build
# OR
npm run build

php artisan serve
```
