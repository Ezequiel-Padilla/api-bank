<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sobre API-BANK

Implementación de una API REST con los endpoints y comportamientos que podemos realizar con una cuenta bancaria, ellos son RETIRO, DEPOSITO, y TRANSFERENCIA.

- Crear un cuenta
```
@POST('/api/register', {
  name: string,
  amount: integer,
  email: string,
  password: string,
  password_confirmation: string
})
```

- Iniciar sesión con una cuenta
```
@POST('/api/login', {
  email: string,
  password: string
})
```

- Ver todas las cuentas
```
@GET('/api/accounts')
```

- Ver una cuenta
```
@GET('/api/accounts/:id')
```

- Actualizar cuenta
```
@PUT('/api/account/:id', {
  name: string,
  amount: integer,
  email: string,
  password: string
})
```

- Ver todos los eventos
```
@GET('/api/events')
```

- Evento retiro
```
@POST('/api/events', {
  source: Account.id,
  type: string,
  amount: integer
})
```

- Evento transferir
```
@POST('/api/events', {
  source: Account.id,
  destiny: Account.id,
  type: string,
  amount: integer
})
```

- Evento deposito
```
@POST('/api/events', {
  source: Account.id,
  type: string,
  amount: integer
})
```

- Logout
```
@POST('/api/logout)
```

- Resetear
```
@POST('/api/logout)
```

Todos los eventos, y las acciones de ver y editar la cuenta no se pueden lograr sin antes haber iniciado sesión o registrándose. Para estas acciones es necesario un token que brindan estos últimos; este token solo dura 5 minutos y se tiene que poner en cada acción que vayamos a hacer. Este token lo podrán obtener desde el mail que se envía.

## Postman Collection

Dejo una Postman Collection en el root del proyecto, donde estan algunas request de prueba.

## Levantar API-BANK

-   `composer install`
-   `php artisan migrate`
-   `php artisan serve`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
