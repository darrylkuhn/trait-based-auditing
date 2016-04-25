## Eloquent trait-based auditing

This is a demonstration of how to use events and trait boostrapping in Eloquent to save-off audit logs.

## How to use this repo

To get started clone the repo and run

```bash
git checkout 1.0
composer install
php artisan migrate
```

The package makes four artisan commands available including:

```bash
user:create
user:show {id}
user:update {id}
user:delete {id}
```

The id may be an integer (a specific user id) or the string "rand" (e.g. `user:show rand`). To see the audit in action
run

```
git checkout 1.1
php artisan user:create
tail storage/logs/laravel.log
```

You should see a line showing that a new record was created. From here you can iterate through the versions in the repo
1.2, 1.3 and 1.4 to see the progression of the implementation. This is really just a demonstration... Need to log
something else? Want to store an IP address? Take it and make it yours... the sky's the limit.