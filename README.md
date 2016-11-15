Git WebAdmin
------------

This is a simple Git WebAdmin tool.


<a href="https://raw.githubusercontent.com/mcka-dev/master/doc/OE60X61.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/OE60X61.png" alt="Git WebAdmin" title="Git WebAdmin"></a>

## Requirements

    Apache >= 2.0
    Apache mod_rewrite
    PHP >= 5.4

## Open source projects used:

- [Slim framework](http://www.slimframework.com/)
- [jQuery framework](http://jquery.com)
- [Bootstrap](http://getbootstrap.com)
- [Bootstrap-select](https://silviomoreto.github.io/bootstrap-select)
- [Bower](https://bower.io)
- [HTML5 Boilerplate](https://html5boilerplate.com)
- [Modernizr](https://modernizr.com)

## Application Settings
### config.ini
```ini
[git]
repositories[] = '/home/git/repositories' ; Path to your repositories
; repositories[] = '/home/repositories2' ; If you wish to add more repositories, just add a new line
; repositories[] = 'C:\Path\to\Repos' ; Path for Windows

links['/home/git/repositories'] = 'http://localhost' ; External links to the repository
; links['/home/repositories2'] = 'http://cgit.localhost/git' ; External links to the repository
```

## Screenshot
<a href="https://raw.githubusercontent.com/mcka-dev/master/doc/Screenshot_Create.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Create.png" alt="Git WebAdmin Screenshot Create" title="Git WebAdmin Screenshot Create"></a>
<a href="https://raw.githubusercontent.com/mcka-dev/master/doc/Screenshot_Delete.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Delete.png" alt="Git WebAdmin Screenshot Delete" title="Git WebAdmin Screenshot Delete"></a>

## REST requests:
`
curl -X POST --data "repo_name=newrepo.git" http://localhost/admin/create
`
```json
{"status":"success","message":"Initialized empty Git repository in /home/repositories/newrepo.git"}
```

`
curl http://localhost/admin/list
`
```json
{"d:\\tmp2":["newrepo.git"]}
```
`
curl -X POST --data "repository=/home/repositories&repo_name=newrepo.git" http://localhost/admin/delete
`
```json
{"status":"success","message":"Repository successfully deleted!"}
```

## TODO:

* rename/move repo
* show description

## License

Code released under the MIT License