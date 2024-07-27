On intial startup of database:
```sh
docker exec -it <db container id or name> /bin/sh
mariadb -u root -p<password>

CREATE USER 'rapidcode'@'%' IDENTIFIED BY "rapidcode123";
GRANT ALL PRIVILEGES ON *.* TO 'rapidcode'@'%';
FLUSH PRIVILEGES;
exit
exit
```

```sh
docker exec -it <db container id or name> /bin/sh
mariadb -u root -p<password>
CREATE USER IF NOT EXISTS user@localhost IDENTIFIED BY 'pass';
GRANT ALL PRIVILEGES ON *.* TO user@localhost;
FLUSH PRIVILEGES;
exit
exit
```

```SQL
CREATE TABLE users (
    username VARCHAR(20) PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    hash VARCHAR(255) NOT NULL
);
```