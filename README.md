# Zoom-API-Wrapper
### steps to setup - using Docker
```sh
git clone https://github.com/ibu123/zoom-api.git
cp .env-example .env
```

| Enviornment Key Name | Value |
| ------ | ------ |
| QUEUE_CONNECTION | database|
| ZOOM_API_KEY | zoom api key |
| ZOOM_API_KEY | zoom secret key |

```sh
docker compose up -d
```

### steps to setup - in localhost

```sh
git clone https://github.com/ibu123/zoom-api.git
composer i
cp .env-example .env
```

- Set Databse details
- Application URL
- Zoom API Keys

| Enviornment Key Name | Value |
| ------ | ------ |
| QUEUE_CONNECTION | database|
| ZOOM_API_KEY | zoom api key |
| ZOOM_API_KEY | zoom secret key |

### Type following commnad

```
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan queue:work
```
> please set header Accept - application/json to get proper validation message.
> you can also download **postman-collection.json** file to test api in postman
