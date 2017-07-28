# Sample User DB Project
This is a sample project on a LAMP environment set up to illustrate my proficiency in creating Web APIs and front-end applications.

#REST API
1. Models
	- User
		- id
		- firstname
		- surname
		- email
		- password
		- country
		- country_code
		- phone
		- creation_date

		- Methods
			- load
			- save

2. Controllers
	- Database (Wrapper)
		- runQuery(query)
		- returnQuery(query)
	- User
		- insert(data)/POST/create
		- get()/GET/read
		- getUser(user_id)/GET/read
		- search(keyword)/GET/read
		- update(data)/PUT/update
		- delete(user_id)/DELETE/delete

3. Views
	- List
	- Edit
	- Create
	- Search

Run 'populate.php' to create the database and tables necessary for the app to function.