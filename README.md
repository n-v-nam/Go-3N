# :fire: GO3.N :fire:
## Requirements:

- Node.js(LTS)
- PHP(>7.x)
- Composer
- MySQL(or the same)

## :clapper: Project setup

> **Run Client:**

```
cd view
npm install
npm run serve
```

> **Run Server:**

```
cd server
cp .env.example .env
composer install
php artisan config:cache
php artisan migrate
php artisan serve
```

> **Create key Laravel:**

```
php artisan key:generate
```

- then copy **key** and patse into **.env**:

```
APP_KEY={{key}}
```

> **Create passport in Laravel to Login**
- in server: __R__

```
php artisan passport:install
```
