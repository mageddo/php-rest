# Introduction
Write REST APIs easily with this framework

# Setting up

## Testing controllers

Controller on specific version

	$ curl -X POST "http://localhost" -H "version: 1.0"
	<h1>It works from post 1.0 PHP API !!!</h1>

Controller on specific method

	$ curl -X POST "http://locahost"
	<h1>It works from post default version PHP API !!!</h1>

Default Controller 

	$ curl -X GET "http://locahost"
	$ curl -X PUT "http://locahost"
	<h1>It works from default PHP API !!!</h1>
