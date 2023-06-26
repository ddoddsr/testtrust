TODO edit vendor/codedge/laravel-fpdf/src/Fpdf/Fpdf.php 
'use Exception' to fix namespace issue;

## Requests
- Backups 
- Audit trail
- Individule logins

## Changes
### Editing / Adding schedules 
- TODO add tooltip on the folllowing
- TODO disallow start time > end time
- removes spaces
- '.' or 'a' expanded to AM and '+' or 'p' expanded to PM


### Progress
  Staff Table
    Rename User to Staff (might find some stragglers)
    Add dept table to Department select
    Add 'created' and 'updated' date-times to Staff table/list
  EmailAlias When adding email Alias, move supervised from Alias to real, delete Bogus


### Import data
  Review Flag 
   first or Last <2 spaces
    TODO effects saved from Staff Record  can reset the review flag

## Wall PDF
### Sizes/
- Up to
- Tot = Col Per Font  RowHt
- 180 = 6 x 30  12    16
- 210 = 6 x 35  10    14  
- 280 = 7 x 40  8.5   12
- 350 = 7 x 50  7     10
- >350  8 x 62  5.5   8
### seeding ANPR
- Add APR seeder for testing staff per page sizes 

## Dashboard Ideas
- TODO Last update from formsite date number of records

## Other Todo
- Notice of success or failure when transferring supervisee
- Supervisor/Staff PDF of info on each Staff supervised

## Staging test run 
### Import Supervisors  (Dan)
- Import full staff ( from 5/1/2023 .env setting)  (Dan)
- Run Dups report and Clean out dups. ReRun and continue cleaning
- Scan Table for any errors
- Set for NO IMPORT

## Production rollout
- Import full staff ( from 5/1/2023 .env setting)  (Dan)
- Run Dups report and Clean out dups. ReRun and continue cleaning
- Scan Table for any errors
- Set for NO IMPORT



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

