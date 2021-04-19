# All In One Compiler

Project goal is to combine multiple PHP files into a single file 📃, to increase performance 🚀 and ease of use 🧑‍💻.

---

Projenin amacı, performansı 🚀 ve kullanım kolaylığını artırmak 🧑‍💻 için birden çok PHP dosyasını tek bir dosyada 📃 birleştirmektir.

## Requirements / Gereksinimler

Tested on Windows 10 with PHP 8.0.1
- Composer
- PHP ^8.0

## Installation / Kurulum
Firstly clone this repo,
````shell
git clone https://github.com/isaeken/alo.git && cd alo
````
And install dependencies.
````shell
composer install
````

## Usage / Kullanımı

Example command:
````shell
php bin/alo.php your/project/directory your_index_file.php output_file.php
````

The command structure:
````shell
/bin/php bin/alo.php <Directory of your project> <The main file of your project. Example: index.php> <Output file path> [--watch=true|false] [--help]
````

### Options
````
--help : Display help message.
--watch=true : Automatically compile enable project on file changes.
````

### Before / Önce
```
`
|- template
|  `- header.php
|  `- footer.php
|- index.php
```

### After / Sonra
```
`
|- output.php
```

## LICENSE

The MIT License (MIT)

Copyright 2021 İsa Eken

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
