## INTRODUCTION

The QED42 Assignment  module is to create a custom entity.

 - Module contains creation of Five field named,_id,city,location,pop,state

## REQUIREMENTS

 - Need to enable migration,migrate_plus,migrate_ui.
 - For connecting a mongodb, we need to add the php extension mongodb

   ### Documents Referred for MongoDB Installtion.
     - https://github.com/ddev/ddev-contrib/pull/122 -


## Documents Refferred.
    - https://www.mongodb.com/docs/manual/tutorial/
    - https://github.com/ddev/ddev-contrib/pull/122
    - https://www.php.net/manual/en/class.mongodb-driver-cursor.php
    - https://www.mongodb.com/community/forums/t/proper-way-to-get-a-php-array-from-aggregate-or-convert-a-mongodb-driver-cursor-to-array/8192
    - https://cloud.mongodb.com/v2/66707691c76523740ef60723#/clusters/commandLineTools/Cluster0

## INSTALLATION

    In ddev/config.yaml ==> Added the webimage_extra_packages: [php8.1-mongodb] to install the mongodb extension.
    - ddev composer require mongodb/mongodb -> It will install the PHP Library
    - Import them Source Mongo db extension in the Mongodbserver in locolhost or other server.
    Import Commands:
    ``` mongorestore --uri mongodb+srv://Admin:<PASSWORD>@cluster0.rkxqlzb.mongodb.net
    ``` mongorestore --db=reporting --collection=employeesalaries dump/test/salaries.bson
    - Install the geofield module by composer.
    - Install the Qed Assignment module will install the necessary modules and fields.
