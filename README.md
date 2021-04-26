## About Explorer Project

A cost explorer to view different types of costs for
various projects that a company is working on for multiple clients. This involves
creating an API endpoint which will be used to fetch cost data.
The API endpoint will fetch cost data from the database, format it in a nested array
format which is similar to a nested folder structure, and return it as a JSON
response.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites

1. Docker: Install docker from https://docs.docker.com/engine/install/

2. Code: Clone the project from : https://github.com/girish-lokapure/explorertask

3. Mysql dump from https://github.com/girish-lokapure/explorertask/blob/master/dumps/mysql-dump.sql

```
git clone https://github.com/girish-lokapure/explorertask.git
```
### Installing
1. Start the Docker containers in the background, you may start Sail in "detached" mode:
```
./vendor/bin/sail up -d
```
2. Import the database dump

3. via Postman or browser try to access the following urls

```
/api/explorer
```
```
/api/explorer?client_id[]=1&client_id[]=2
```
```
/api/explorer?cost_type_id[]=1&cost_type_id[]=10
```
```
/api//explorer?cost_type_id[]=7&project_id[]=32&project_id[]=16
```

## Running the tests

1 . Access the running docker sail container by running docker exec.
```
docker exec -it <container id of sail> /bin/bash
```
2. Within the container run the following to do the tests
```
./vendor/bin/phpunit
```

## Built With

* [laravel](https://laravel.com/) - The web framework used
* [Mysql](https://www.mysql.com/) - Database

## Authors

* **Girish Lokapure** - *Initial work* 

