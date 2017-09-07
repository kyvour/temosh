# 1. Prepare test data:

You can import test data to the new database/collection using prepared data from restaurants.json file.
In a case when you have installed `mongoimport` tool, you can use following command:
`mongoimport -u USERNAME -p PASSWORD --authenticationDatabase=AUTHDB --db DB_FOR_DATA_IMPORT --collection COLLECTION_NAME --file ./resources/restaurants.json`

For example:
`mongoimport -u root -p root --authenticationDatabase=admin --db temosh --collection restaurants --file ./resources/restaurants.json`

**!NOTE: The user should have *readWrite* access for the selected database.**
