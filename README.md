# Junior PHP developer task

### Running and usage
* step 1: in the command line run:
```bash
composer install
```
* step 2: set up your .env file.
* step 3: set up your email configuration. It is used gmail mailing transoporter. But you can change to another one: https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
* step 4. in the command line run:
```bash
php bin/console doctrine:migrations:migrate
```
* step 5: open postman or anyother tool for making REST API queries.
* step 6. create your first user
* step 7: check your email to verify the user
* step 8: the command to run the checker via command line is:
```bash
php bin/console app:check_currency
```

### API endpoints
## POST
`any client` [/auth/user/registration]<br/>
`has auth middleware` [/currency/create]<br/> must have header X-AUTH-TOKEN

### POST /auth/user/registration
Create the user and subscribe for the currencies

**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `firstname` | required | string  | User firstname.                                                                      |
|     `surname` | required | string  | User lastname.                                                                     |
|     `email` | required | string  | User email.                                                                     |
|     `phone_number` | required | string  | User phone number. Can not start with 0 and must have 9 digits                                                                     |
|     `bday` | required | date  | Must have the format YYYY-MM-DD.                                                                     |
|     `currencies` | optional | array  | Array of currencies.                                                                     |

### POST /currency/create

**Parameters**
Parameters also applies to the previous endpoint param of currency. Must have header "X-AUTH-TOKEN" with a given token that was sent while email verification.
|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `currency-name` | required | string  | curency code.                                                                      |
|     `min` | required | float  | Min value of the currency.                                                                     |
|     `max` | required | float  | Max value of the currency                                                                     |
|     `bday` | required | date  | Must have the format YYYY-MM-DD.                                                                     |
|     `currencies` | optional | array  | Array of currencies.     
