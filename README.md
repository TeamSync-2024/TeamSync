# TeamSync

TeamSync is a collaborative to-do task list application that allows users to create task lists, add items, and work together with teammates by assigning tasks to specific individuals. It is designed to enhance productivity and ensure that teams stay on track with their tasks.

## Features

- **Create Task Lists** – Organize tasks into separate lists for better management.
- **Add Items to Lists** – Easily add tasks to your lists.
- **Assign Tasks** – Assign tasks to specific teammates to distribute workload effectively.
- **Collaborative Work** – Work together with your team in real-time.
- **Task Status Updates** – Mark tasks as complete to track progress.
- **User-Friendly Interface** – Simple and intuitive design for seamless task management.

## Usage

1. **Create an Account:** Sign up and log in to start using TeamSync.
2. **Create a List:** Add a new task list to categorize your tasks.
3. **Add Tasks:** Populate your list with tasks that need to be completed.
4. **Assign Tasks:** Assign tasks to your teammates to delegate responsibilities.
5. **Track Progress:** Mark tasks as completed to keep the team updated.

## Running the Project with Docker

To run TeamSync as a Dockerized web application, follow these steps:

### Prerequisites
Ensure you have the following installed:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Project Setup

1. **Organize the project structure:**
   - Store all PHP, HTML, CSS, and JavaScript files in the `./php` directory.
   - Place your MySQL database dump files in the `./mysql-init` directory.
     - Ensure the file `./mysql-init/0-create-db.sql` exists.
     - Replace `./mysql-init/1-sample-table.sql` or add new SQL files with higher numerical prefixes to execute them in order.

2. **Review Database Configuration:**
   - The database details are specified in `docker-compose.yml`.
   - The default MySQL credentials are:
     - **Database Name:** `di_internet_technologies_project`
     - **Username:** `webuser`
     - **Password:** `webpass`
     - **Root Password:** `rootpass`
     - **Host:** `127.0.0.1`
     - **Port:** `3307` (mapped from 3306 in the container)

### Running the Application

1. Open a terminal and navigate to the project's root directory.
2. Run the following command to start the containers:
   ```sh
   docker-compose up --build
   ```
3. Once the containers are up, access the web application by visiting:
   - **Frontend/Web App:** `http://localhost:8080`
   - **MySQL Database:** Connect using `127.0.0.1:3307` with a MySQL client like DBeaver or MySQL Workbench.


Now you're ready to use TeamSync! 🚀

