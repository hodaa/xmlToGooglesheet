FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    zip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash -s -- --install-dir=/usr/local/bin

# نسخ المشروع
COPY . .

# تثبيت التبعيات
# RUN composer install --no-interaction --optimize-vautoloader

# فتح المنفذ
EXPOSE 8000

# تشغيل Symfony server
CMD ["symfony", "serve", "--port=8000", "--no-tls"]
