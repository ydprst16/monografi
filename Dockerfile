FROM php:8.2-cli

WORKDIR /app

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project
COPY . .

# Railway pakai PORT dynamic
ENV PORT=8080

# Run PHP built-in server
CMD php -S 0.0.0.0:$PORT