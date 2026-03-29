FROM php:8.1-apache

# 日本語ロケール設定
RUN apt-get update && apt-get install -y \
    locales \
    && echo "ja_JP.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen ja_JP.UTF-8 \
    && rm -rf /var/lib/apt/lists/*

ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8
ENV TZ Asia/Tokyo

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 必要な拡張機能をインストール
RUN apt-get update && apt-get install -y libicu-dev libonig-dev && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli intl mbstring

# 拡張機能を有効化
RUN docker-php-ext-enable pdo_mysql mysqli intl mbstring

# Composerをインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP設定で日本語を対応
RUN echo 'default_charset = "UTF-8"' >> /usr/local/etc/php/conf.d/charset.ini && \
    echo 'mbstring.language = Japanese' >> /usr/local/etc/php/conf.d/charset.ini && \
    echo 'mbstring.internal_encoding = UTF-8' >> /usr/local/etc/php/conf.d/charset.ini && \
    echo 'mbstring.http_input = UTF-8' >> /usr/local/etc/php/conf.d/charset.ini && \
    echo 'mbstring.http_output = UTF-8' >> /usr/local/etc/php/conf.d/charset.ini && \
    echo 'mbstring.encoding_translation = On' >> /usr/local/etc/php/conf.d/charset.ini

# Apacheのモジュールを有効化
RUN a2enmod rewrite

# Apacheの設定 - CakePHPのwebrootをドキュメントルートに設定
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/webroot|g' /etc/apache2/sites-available/000-default.conf

# Apacheの<Directory>ディレクティブを設定
RUN cat > /etc/apache2/sites-available/000-default.conf << 'EOF'
<VirtualHost *:80>
    ServerName localhost
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/webroot

    <Directory /var/www/html/webroot>
        AllowOverride All
        Require all granted
        
        # Content-Type で UTF-8 を明示指定
        AddDefaultCharset utf-8
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php/$1 [L]
        </IfModule>
    </Directory>

    <Directory /var/www/html>
        AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# ディレクトリのパーミッション設定
RUN mkdir -p /var/www/html/tmp /var/www/html/logs && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 777 /var/www/html/tmp && \
    chmod -R 777 /var/www/html/logs

WORKDIR /var/www/html

EXPOSE 80

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
