# instalation
## python
1. pip install --upgrade pip setuptools wheel
2. pip install opencv-python (jika pake python2.7, installnya pake pip install opencv-python==4.2.0.32)

## composer
1. composer install


## env
1. cp .env.example .env
2. php artisan key:generate
3. add config to env


a. linux:
uploadFolder=/home/dodev/upload


b. windows:
uploadFolder=C:\\xampp\\htdocs\\uts


## migrate
php artisan migrate


# menu
localhost:8000/login
localhost:8000/register
localhost:8000/upload
