# Reservation API

### Project Info

- Nginx
- PHP 8.1
- Symfony 7
- Mysql 8
- Redis


## Install & Run Docker

```
git clone https://github.com/enginkartal/simple-reservation

composer install

docker-compose up -d --build

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load


127.0.0.1:9100/api/v1

```

## Migration

```
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load

```


## Endpoint List

| Method | Endpoint                                                   |
|--------|------------------------------------------------------------|
| GET    | /api/v1/rooms?check_in=2023-03-09&check_out=2024-03-23&guest=1 |
| GET    | /api/v1/rooms/{id}                                         |
| GET    | /api/v1/reservations                                       |
| GET    | /api/v1/reservations/{ref}                                 |
| POST   | /api/v1/reservations                                       |
| DELETE | /api/v1/reservations/{ref}                                       |


## OpenApi (Swagger) API Doc

```
http://127.0.0.1:9100/api/doc
```

## Postman Collection
```
missafir.postman_collection.json

```

## License

MIT License
