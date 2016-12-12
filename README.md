# Phar Creator

Create a phar of your project folder
```shell
php index.php -r=~/my-projects/hero/
```

Create a phar with your project folder and define the index.php like the stub
```shell
php index.php -r=~/my-projects/hero/ -s=index.php
```
Create a phar without any comments
```shell
php index.php -r=~/my-projects/hero/ -s=index.php -c=true
```

Create your phar and save the output on dist dir of your app
```shell
php index.php -r=~/my-projects/hero/ -s=index.php -o=~/my-projects/hero/dist
```

Do all and show what is doing
```shell
php index.php -r=~/my-projects/hero/ -s=index.php -o=~/my-projects/hero/dist/hero.phar -v=true
```

Use a "." (dot) in _-o_ parameter
```shell
php index.php -r=~/my-projects/hero/ -s=index.php -o=~./dist/hero.phar -v=true
```

Extract phar file
```
php -r '$phar = new Phar("phar20161212C.phar"); $phar->extractTo("./phar20161212C");'
```