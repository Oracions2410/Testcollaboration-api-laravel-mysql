## Test Collaboration Laravel MySQL API

### Setup project

**Install dependencies**
> composer install

**Make migrations**
> php artisan migrate

**Passport initialisation**
> php artisan passport:install


**Start server**
> php artisan serve

### Auth API routes
* [post]                **/api/login**     - login with credentials
* [post]                **/api/register**  - Create account
* [post|Get|Put|Delete] **/api/Cars**      - Basic CRUD API
* [post]                **/google/redirect** - login with google
