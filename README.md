Git WebAdmin
------------

This is a simple Git WebAdmin tool.

Adaptive web design.
<a href="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/OE60X61.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/OE60X61.png" alt="Git WebAdmin" title="Git WebAdmin"></a>

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

### Set File Permissions
Make file executable:
```bash
sudo chmod +x git-init.sh
sudo chmod +x git-rm.sh
```

To ensure correct access rights to the repository, the entire folder `repositories/` should be owned by `www-data` or a user member of `www-data` group.

```bash
sudo chown -R www-data:www-data /home/git/repositories
```

## Screenshot
<a href="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Create.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Create.png" alt="Git WebAdmin Screenshot Create" title="Git WebAdmin Screenshot Create"></a>
<a href="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Delete.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Delete.png" alt="Git WebAdmin Screenshot Delete" title="Git WebAdmin Screenshot Delete"></a>
<a href="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Two_Repository.png"><img src="https://github.com/mcka-dev/GitWebAdmin/blob/master/doc/Screenshot_Two_Repository.png" alt="Git WebAdmin Two Repository" title="Git WebAdmin Two Repository"></a>


## REST requests:
```bash
curl -X POST --data "repo_name=newrepo.git" http://localhost/admin/create
```
```json
{"status":"success","message":"Initialized empty Git repository in /home/repositories/newrepo.git"}
```

```bash
curl http://localhost/admin/list
```
```json
{"/home/repositories":["newrepo.git"]}
```

```bash
curl -X POST --data "repository=/home/repositories&repo_name=newrepo.git" http://localhost/admin/delete
```
```json
{"status":"success","message":"Repository successfully deleted!"}
```

## TODO:

* Rename/move repo
* Show description

## License

Code released under the <a href="https://github.com/mcka-dev/GitWebAdmin/blob/master/LICENSE">MIT License</a>