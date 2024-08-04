# Note That Down!
Welcome to Note That Down!, a stylish and functional note-taking web application that helps you keep your thoughts organized and visually appealing.

## Features
1) User Authentication: Securely sign up and log in to manage your personal notes.
1) Colorful Notes: Customize your notes with a variety of colors to make them stand out.
1) Visual Layout: Enjoy a clean, masonry-style layout that arranges your notes in a dynamic and engaging way.

## Technology Stack
- Backend: PHP
- Database: MariaDB

## Developer Guide
To get started, clone the repository and follow the installation instructions below.

1) Clone the repository
    ```bash
    git clone https://github.com/yourusername/note-that-down.git
    cd note-that-down
    ```
1) Install [docker](https://docs.docker.com/engine/install/) along with [docker-compose](https://docs.docker.com/compose/install/) if not already installed
1) Set up mysql environment variables
    ```bash
    echo "Your-mysql-root-password-here" > secrets/mysql_root_password.txt
    echo "Your-mysql-username-here" > secrets/mysql_username.txt
    echo "Your-mysql-user-password-here" > secrets/mysql_user_password.txt
    ```
1) Start the swarm using docker-compose
    ```bash
    docker-compose up -d
    ```

The web app is now running and accessible at [http://localhost:80](http://localhost:80)
> Note: Give few minutes for mariadb container to initialize and become availble on first startup.

### Optional
The following steps are **not required** but might be handy for debugging
1) You can log into the database by first entering the docker container's shell
    ```sh
    docker exec -it <db container id or name> /bin/sh
    ```

1) Then executing the following to log into mariadb server
    ```sh
    mariadb -u root -p<password>
    ```

> Note: Make sure to replace '< password >' and '< db container id or name >' with appropriate values