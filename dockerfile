# Use a base image (this is a common one for Yii2 apps with PHP and Apache)
FROM yiisoftware/yii2-php:8.1-apache

# Install necessary CA certificates to fix SSL issues with Composer
RUN apt-get update && apt-get install -y ca-certificates
RUN update-ca-certificates

# Copy the 000-default.conf file to the correct location
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the site configuration (optional, but recommended)
RUN a2ensite 000-default.conf

# Install cron (if not already installed)
RUN apt-get update && apt-get install -y cron

# Copy the cronjobs file into the container
COPY cronjobs /etc/cron.d/cronjobs

# Set the appropriate permissions for the cronjobs file
RUN chmod 0644 /etc/cron.d/cronjobs

# Ensure the cron service starts and cronjobs are loaded
RUN crontab /etc/cron.d/cronjobs

# Restart cron service and apache
RUN service cron restart
RUN service apache2 restart

# Set the working directory
WORKDIR /var/www/html

# Expose ports (optional, but useful if you want to access the app on specific ports)
EXPOSE 80

# Start cron and Apache in the foreground
CMD cron && apache2-foreground
