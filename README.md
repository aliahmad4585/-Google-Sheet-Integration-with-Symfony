# Google-Sheet-Integration-with-Symfony
Write a command-line program, based on the Symfony CLI component (https://symfony.com/doc/current/components/console.html). The program should process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API (https://developers.google.com/sheets/)

# Important Note
Currenty I've upload the .ENV file with all credentials for testing purpose. If you want add own google sheet credentials, replace the credentials in  .ENV file.

# Prerequisite
 1. PHP >= 8.1
 2. Symfony 6.1
 3. PHPUnit for Testcases
 4. Composer
 5. Docker Desktop 
 6. Docker compose engine
 

# How to setup the repository

**Method 1 Via create docker file**
 1. Git clone https://github.com/aliahmad4585/-Google-Sheet-Integration-with-Symfony.git
 2. Checkout to master branch "**git checkout master**"
 3. Run "**composer install**" to install the dependencies
 4. Run "**docker-compose build**" to make a container
 5. Run "**docker-compose up -d**" to run the container
 6. Click on **view in browser** button to check that container is running
 7. Open the Docker container CLI and type "**php bin/console app:upload-data**" to upload data into Google Sheet

**Method 2 Via import the docker file**
 1. Download the docker container from the following link [Google Sheet Upload data Container](https://drive.google.com/file/d/1xM0_3IZGWSg284h_dHdKoXtv3m_aakq_/view?usp=sharing)
 2. import the container to docker desktop
 3. Run the Docker container by clicking on start button
 4. Click on **view in browser** button to check that container is running
 5. Open the Docker container CLI and type "**php bin/console app:upload-data**" to upload data into Google Sheet


**Note**

If you guys want to test with my .ENV credentials then prefer to use the method 2.

if you guys want to change the .ENV credentials then user Method 1.



# Which patterns have been used?
 1. Dependency injection pattern
 2. Dependency Injection Container
 3. Factory Design pattern
 4. SOLID Principles
 
 # How easy is it to set up the environment and run your code?
  1. Run on local machine
  2. Run on Docker machine
 
# Have you applied SOLID and/or CLEAN CODE principles?
Yes. I've applied the solid principles on all the classess

# Are tests available and how have they been set up?
Yes. I've written the unit test cases for Service classes and Command. I've used phpunit to write the test cases.

**Two ways to run the unit test cases**
1. When building a docker container, it will first run the unit test cases then build the container if all the test are passed.
2. **php bin/console** to run the test cases locally
