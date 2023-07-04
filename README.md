## GoLive
sltrust01.internal.ihopkc.org
SMTP 172.17.121.38
pltrust01.internal.ihopkc.org

Change Password
- TODO  After composer update or install
  - sudo vim vendor/codedge/laravel-fpdf/src/Fpdf/Fpdf.php 
  - after namespace to fix namespace issue;
  - 'use Exception;' 
  
- √ SMTP setup email outgoing  requested / ticket opened
- π Staging copy of Production  help requested scp not working
- ® VPN - should request
- ® https setup requested  process started not realy clear on propgress
- ® Wall PDF 'fixed' font size  Need info what to do fior > 210 staff with 6x35 page full
- ∫ Fix PDF!!!!!  Must refresh page if button press not working.  Help asked on Discord
- √ Fix default ! is_admin and ! is_supervisor on new
- √ add default effective date   (how?? persistance for a stack of STs all the same date)?
- √ Less scary buttons archive not delete on Staff
- √ FIX Mission Statement!!
- √ Add schedule s/b add "schedule line"
_ √ is_approved  ie: pending approval by supervisor
- √+ why ben nunez is review  -- expand char list  turned off
- √+ top search bar broken full_name  removed

## Check on staff internet
loading my-styles from build

## Roadmap
- Supervisor/Staff PDF of info on each Staff supervised

### 1.x In production
- √ audit by user 1.1
- roles / permissions by user role

### 2.0
- add service hours
- notes for Admin users
  - morph belongs to user set dept & etc.
    - requires additional info
      - who can see
      - when to apply?
- should we use 'TAGS' ?
### 3.0

### 4.0

## Requests / Questions
- Backups: Handled by VMware
- Audit trail: by laravel & filament packages requires some tweaking
- Individule logins:  Yes
  - Who setup? via admin
  - password reset needs email works with mailpit local

## Changes
### Editing / Adding schedules 
- TODO add tooltip on the folllowing
- TODO disallow start time > end time
- removes spaces
- '.' expanded to ':'
- '-' or 'a' expanded to AM and '+' or 'p' expanded to PM


### Progress
- Staff Table
-- Rename User to Staff (might find some stragglers)
  - Add dept table to Department select
  - Add 'created' and 'updated' date-times to Staff table/list
- EmailAlias When adding email Alias, move supervised from Alias to real, delete Bogus
- Notice of success or failure when transferring supervisee

### Import data
  Review Flag 
   first or Last <2 spaces
  - feature or bug? When Staff Record saved applies the rules to change the review flag, 

## Wall PDF
### Sizes/  re test and review

- Tot = Col Per Font  RowHt
- √< 181 = 6 x 30  12    16
- √< 211 = 6 x 35  11    14.4
- ®< 246 = 6 x 40  9.5   13 
- ®< 285 = 7 x 40  8.4   9.5
- ®< 350 = 7 x 50  7     10
- ®>350  8 x 62  5.5    8
### seeding ANPR
- Add APR seeder for testing staff per page sizes 

## Dashboard Ideas
- TODO Last update from formsite date number of records


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

## Preproduction Tasks / bugs
- 

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

### Mailpit 
see https://golangexample.com/an-email-testing-tool-for-developers/
- browser http://0.0.0.0:8025/
- .env
  - MAIL_MAILER=smtp
  - MAIL_HOST=localhost
  - MAIL_PORT=1025

  ## bug in fpdf look at font name 39335_UniversCondensed.php
  - in resources/fonts/UniversCondensed.php