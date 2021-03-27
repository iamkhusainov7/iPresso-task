# Junior PHP developer task

### Running and usage
* step 1: in the command line run "composer install".
* step 2: set up your .env file.
* step 3. in the command line run "php bin/console doctrine:migrations:migrate"
* step 4: open postman or anyother tool for making REST API queries.
* step 5. use this endpoint to make a user: /auth/user/registration. Here the form body must have this structure:
    firstname => string,
    surname   => string,
    email     => string,
    phone_number => int (not starting with 0, with length of 9)
    bday      => date (YYYY-MM-DD)
    currencies => array() => where each item has such a structure:
        currency-name
        min
        max
* step 6: check your email to verify the user
* step 7: the command to run the checker via command line is: "php bin/console app:check_currency"
* step 8: if you want to add manually the currency subscription then, you can do that via this endpoint: /currency/create. Must have header "X-AUTH-TOKEN" with a given token while email verification

