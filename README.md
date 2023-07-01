# Các yêu cầu để chạy:
# các thư công cụ yêu cầu cần cài đặt: composer, php 8.1 và các package liên quan của php 8.1
# chạy các lệnh:
    composer install
    cp .env.example .env

# Tại file .env, cập nhật file như sau và tạo database với tên là healthyfoodstore ở mysql

DB_CONNECTION=mysql          
DB_HOST=127.0.0.1            
DB_PORT=3306                 
DB_DATABASE=healthyfoodstore       
DB_USERNAME=root             
DB_PASSWORD= 

# tiếp tục chạy các lệnh:
    php artisan key:generate
    php artisan migrate:fresh --seed
    php artisan storage:link

# chạy lệnh sau để chạy ở môi trường local:
    php artisan serve