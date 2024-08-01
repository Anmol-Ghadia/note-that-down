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

# entering db's shell
```sh
docker exec -it <db container id or name> /bin/sh
mariadb -u root -p<password>
```

```SQL
CREATE TABLE users (
    username VARCHAR(20) PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    hash VARCHAR(255) NOT NULL
);
```


<!-- ```SQL
CREATE TABLE notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username)
);
``` -->

```SQL
CREATE TABLE notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    color CHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (LENGTH(color) = 6 AND color REGEXP '^[0-9A-Fa-f]{6}$'),
    FOREIGN KEY (username) REFERENCES users(username)
);
```