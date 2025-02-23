# AgroApp Project Setup

This project uses **Docker** for containerization. Follow the steps below to set up and run the project.

## Prerequisites

- You will need **Docker** installed on your machine.
- If you already have **Docker Desktop** running, you are good to go.

## Project Setup

### Step 1: Copy `.env` File
- Copy the `.env` file to the root of the project directory.
  - If you don’t have an `.env` file, you can copy it from the provided example or from the one I gave you.

### Step 2: Build Docker Containers
- Open a **Command Prompt** (CMD) or **Terminal** in the project folder.
- Build the Docker containers for the project.

### Step 3: Start Docker Containers
- After building the containers, start them in detached mode so they run in the background.

### Step 4: Access the Docker Container
- Once the containers are up and running, access the container's shell.

### Step 5: Update Composer Dependencies
- Inside the container, update the Composer dependencies to ensure that all required PHP packages are installed or updated.

### Step 6: Run Database Migrations
- Run the necessary database migrations to set up the database schema.

---

### Step 7: Import Data (Optional)

You can create data in the database either by using the app or importing it manually.

#### Option 1: Create Data through the App
- You can use the app's interface to add data directly to the database. This allows you to create and manage data through the app's UI, depending on the features of the app.

#### Option 2: Import Data Manually
- If you have a database dump (e.g., `.sql` file), you can import it manually.
  - You can use a database management tool like **PHPMyAdmin** or **Adminer** to import the `.sql` file into the database.
  - Alternatively, you can execute SQL commands inside the Docker container.

---

### Step 8: Stop Docker Containers (Optional)

Once you're done with the project, you can stop the containers and clean up the environment.

### Step 9: Clean Up Docker (Optional)

To clean up unused Docker resources (like stopped containers, networks, and dangling images), you can run the cleanup command.

---

## Commands Overview

Once you’ve completed all the setup steps, here’s a summary of the important commands used in the setup process:

**Commands**:
   ```bash
   docker-compose build  # Builds the Docker containers for the project
   docker-compose up -d  # Starts the containers in detached mode (background)
   docker exec -it agroApp bash  # Accesses the shell of the running container
   composer update  # Updates the PHP dependencies using Composer
   ./yii migrate  # Runs the migrations to set up the database
