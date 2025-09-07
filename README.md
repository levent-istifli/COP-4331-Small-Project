# Team 21 Contact Manager

A full-stack contact management application built with a LAMP (Linux, Apache, MySQL, PHP) backend and a Javascript/HTML/CSS frontend. 

## Features
Backend API → Secure PHP endpoints that handles user accounts and contacts.<br/>
Frontend UI → A responsive interface where users can sign up, login, add, search, update, and delete their contacts.<br/>

## Setup

#### 1. Cloning the Repository
```bash
git clone git@github.com:levent-istifli/COP-4331-Small-Project.git
```

#### 2. Database Setup
Import database schema into MySQL:
```bash
mysql -u root -p db_name < schema.sql
```

#### 3. Backend Configuration
Edit ```config.php``` with your database credentials:
```php
define("DB_HOST", "localhost"); // or the host's ip address
define("DB_USER", "db_user");
define("DB_PASSWORD", "db_user_pwd");
define("DB_NAME", "name_of_db");
```

## API Overview
The backend exposes REST-style enpoints under '/LAMPAPI'

| Endpoint        | Method | Description      |
|-----------------|--------|------------------|
| `/LAMPAPI/signup.php` | **POST**   | Register new user   |
| `/LAMPAPI/login.php`  | **POST**   | Authenticate user   |
| `/LAMPAPI/add_contact.php`    | **POST**        | Create contact     |
| `/LAMPAPI/search_contact.php`   | **GET/POST**    | Read/Search contacts |
| `/LAMPAPI/update_contact.php` | **POST/PUT**    | Update contact     |
| `/LAMPAPI/delete_contact.php` | **POST/DELETE** | Delete contact     |

### Example of API Interaction

Adding a user (POST `/LAMPAPI/signup.php`): <br/>

Request:
```json
{
    "firstname": "John",
    "lastname": "Smith",
    "login": "jsmith",
    "password": "thisisgroup21" 
}
```

Response:

If user already exist:
```json
{
    "Error": "Login already exist"
}
```

If user does not exist:
```json
{
    "Error": ""
}
```

## Authors
Gradi Mbuyi → Database / API → [gradimbuyi@outlook.com](mailto:gradimbuyi@outlook.com) <br/> 
Levent Istifli → API / Project Manager <br/>
Trevor Chesley → Frontend / UI

 