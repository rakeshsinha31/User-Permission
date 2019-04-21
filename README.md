# Simple User management Sytem

### Requirements
```
PHP 7.1
Symfony 4.2.7
sensio/framework-extra-bundle
okta/jwt-verifier 
spomky-labs/jose 
guzzlehttp/psr7
Doctrine
mysql
```

### Directory/File organization

The Source directory:

```
Controllers - Contains User, Group and API Controllers. The business logics are written here.
Entity - Contails the models and utility to access the model data. The . utilities are create 
by Symfony out of the box.
Migrations - Database migration files.
Reposiroty - Contails code to interacts with a DB. There are default methods (commented)
created by symfony. 
```
Documents
```
Data_mode_ERD.pdf - Contains the data model and Database ER diagram.
openapi_doc.yaml - Detailed API docoment. Each enpoints are described with Request & Response body. 
Open the file in swagger (https://editor.swagger.io/) for detailed view.


### Testing
No test cases written yet.
