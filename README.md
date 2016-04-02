# Introduction
Write REST APIs easily with this framework

# Setting up

## Testing controllers

Controller on specific version

```bash
$ curl -X POST "http://localhost" -H "version: 1.0"
<h1>It works from post 1.0 PHP API !!!</h1>
```

Controller on specific method

```bash
$ curl -X POST "http://locahost"
<h1>It works from post default version PHP API !!!</h1>
```

Default Controller 

```bash
$ curl -X GET "http://locahost"
$ curl -X PUT "http://locahost"
<h1>It works from default PHP API !!!</h1>
```

## Runinng from docker

```bash
	$ gradle build-dev
```

That way you can access the docker container IP and use the API

# License

This project is released under version 2.0 of the [Apache License][].
[Apache License]: http://www.apache.org/licenses/LICENSE-2.0