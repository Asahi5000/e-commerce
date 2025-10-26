# Use official PHP Apache image
FROM php:8.2-apache

# Copy project files to web directory
COPY . /var/www/html/

# Expose port 10000 for Render
EXPOSE 10000

# Change Apache port to match Render's expected port
RUN sed -i 's/80/10000/' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache rewrite (useful for frameworks like Laravel)
RUN a2enmod rewrite
