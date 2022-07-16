# Expenses app in PHP
![alt text](https://github.com/Behelit0906/expense-app/blob/master/screenshots/dashboard.png)

Web application to keep track of personal expenses. Made to practice my web development skills. 

Some of the skills applied are:

- Model-View-Controller design pattern
- HTML, CSS, JavaScript and PHP
- MySQL queries
- Asynchronous requests
- User authentication and registration
- Authorization by roles
- Graph integration
- Use of sessions


## Installation

1. Clone the repository: https://github.com/Behelit0906/expense-app.git
2. Import database
    
    Import the sql file to our MySQL, the sql file is located in the root folder of the project with the name expenses.sql
     
3. Configure database access credentials

    Go to the config.php file located in app/config/ and modify the values of the constants HOST DBNAME, USER and PASSWORD by the corresponding values to connect to the database.valores     


4. Installing dependencies with composer
      
      In the root folder of the project open a terminal and run the command **composer install**
   
   
5. Configure domain name

      Go to the JavaScript common-methods file located in public/js/ and change the value of the domain constant to the domain address you are going to use.
      
      Go to the config.php file located in app/config/ and modify the value of the URL constant to the domain you are going to use.
      
  

