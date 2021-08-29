<h1 align="center">Zé Delivery Partners API 🍺</h1>
<p>
    <img src="https://shields.io/badge/LARAVEL-%5E8.0-red?logo=laravel">
    <img src="https://shields.io/badge/PHP-%5E7.4-blue?logo=php" />
    
</p>

> Zé Delivery Partners API Code Challange

## Prerequisites

<li><a href="https://www.docker.com/get-started">Docker</a></li>
<li><a href="https://docs.docker.com/compose/">Docker Compose</a></li>

## Local Setup
Clone this repo and shell to its path and run docker compose. Make sure to have 5432, 6379, 8787 and 8888 available locally 

```sh
git clone https://github.com/olivic9/desafio-ze.git
cd desafio-ze
docker-compose up -d
```

Setup the local container
```sh
docker exec -it ze_php composer install
docker exec -it ze_php php artisan migrate --seed
```

## Production Setup


- Clone this repo and then, build and push a production image to your image registry
```sh
git clone https://github.com/olivic9/desafio-ze.git
cd desafio-ze
docker build -t your_image_register/image_name:image_tag .
docker your_image_register/image_name:image_tag
```

- Create a container with this image and  map the .env.example vars inside your container


## Usage

All endpoints are under http://localhost:8888 check <a href="http://localhost:8787/">Swagger</a> docs for further info.

## Run tests

```sh
docker exec -it ze_php php artisan test
```

## Author

👤 **Clayson Oliveira**

* Linkedin: [Clayson Oliveira](https://www.linkedin.com/in/clayson-oliveira-603a853b/)
* Github: [@olivic9](https://github.com/olivic9)