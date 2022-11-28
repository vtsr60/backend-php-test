AskNicely PHP backend skill test
==========================


### Application
The TODO App allows a user to add reminders of thing he needs to do. Here are the requirement for the app.
* Users can add, delete and see their todos.
* All the todos are private, users can't see other user's todos.
* Users must be logged in order to add/delete/see their todos.

Credentials:
* username: **user1**
* password: **user1**

#### Homepage:
![Homepage](/web/img/homepage.png?raw=true "Homepage")

#### Login page:
![Login page](/web/img/login-page.png?raw=true "Login page")

#### Todos:
![Todos](/web/img/todos.png?raw=true "Todos")

### Requirements
* php 5.3+
* mysql
* A github account

### Installation
**/!\ You need to fork this repository. See [How to submit your work?](#how-to-submit-your-work)**
```sh
php composer.phar install
cp config/config.yml.dist config/config.yml
mysql -u root <database> < resources/database.sql
mysql -u root <database> < resources/fixtures.sql
php -S localhost:1337 -t web/ web/index.php
```
You can change the database connection from the file `config/config.yml`.

### Instructions

You will be asked to improve the code of this app with the following tasks.

You can complete the tasks in any order.

### What we are looking for?
1. Separate your <b>commits by task</b> and use the following format for your commit messages: TASK-{task number}: {meaningful message}
2. We care about UI/UX, any attention to detail in the UI will be noticed. Please dont hack in UI changes. 
3. Simple clear code comments are helpful.   

### Tasks
* TASK 1: As a user I can't add a todo without a description.
* TASK 2: As a user I can mark a todo as completed.
    - Write a database migration script in `resources/`
* TASK 3: As a user I can view a todo in a JSON format.
    - Ex: /todo/{id}/json => {id: 1, user_id: 1, description: "Lorem Ipsum"}
* TASK 4: As a user I can see a confirmation message when I add/delete a todo.
    - Hint: Use session FlashBag.
* TASK 5: As a user I can see my list of todos paginated.
* TASK 6: Choose a task below:
    - BACKEND (focus): Implement an ORM database access layer so we don’t have SQL in the controller code, or
    - FRONTEND (focus): Use JQuery, VueJs, or React to render the todo list dynamically and allow the delete + completed buttons to work dynamically via Ajax. You do not need pagination to work within your new front end.  We care about the user experience here -- this might be animation?  
 
    

Extra tasks:
- Fix any bugs you may find.
- Fix any security issues you may find.
- Adding a few unit tests to show us that you understand how they work is a bonus. 

### How to submit your work?

1. ##### First you need to fork this repository.
![Forking a repo](/web/img/fork.png?raw=true "Forking a repo")

2. ##### Then clone your fork locally.
![Cloning a repo](/web/img/clone.png?raw=true "Cloning a repo")

3. ##### Install the app locally. See the [Installation Guide] (#Installation).

4. ##### Once you've completed your work, you can submit a pull-request to the remote repository.
![ a Pull Request](/web/img/pull-request.png?raw=true "Creating a Pull Request")

5. ##### Review your changes and validate.
![Validating a Pull Request](/web/img/pull-request-review.png?raw=true "Validating a Pull Request")



And you're done!


More documentation on Github:
* https://help.github.com/articles/fork-a-repo/
* https://help.github.com/articles/using-pull-requests/

### Raj Changes

#### Added Composer Package
* [doctrine/common](https://github.com/doctrine/common/tree/v2.10.0)
* [dflydev/doctrine-orm-service-provider](https://github.com/dflydev/dflydev-doctrine-orm-service-provider)
* [symfony/orm-pack](https://github.com/symfony/orm-pack/tree/v2.2.0)
* [symfony/test-pack](https://github.com/symfony/test-pack/tree/v1.0.9)

#### Task 6 BACKEND (focus): Implement an ORM database access layer so we don’t have SQL in the controller code
1. Add ORM pack to the project.
2. Add Todo and User Entity.
3. Refactor controllers to move the logic from single file to classes.
4. Creating the service to handle business logic.

#### Extra task: Added unit test for AuthService
1. Add PHPUnit config and bootstrap file
2. Add Unit test for AuthService
3. Run the PHPUnit using ```.\bin\test.cmd```

#### TASK 4: As a user I can see a confirmation message when I add/delete a todo.
1. Add MessageService to interact with Flashbag
2. Add Exception/Error handling to application(404)
3. Add confirmation messages to the when add/delete a todo
