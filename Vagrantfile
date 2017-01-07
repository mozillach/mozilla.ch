# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/xenial64"

  config.vm.network "forwarded_port", guest: 8000, host: 8000

  # provisioning with a shell script.
  config.vm.provision "shell", inline: <<-SHELL
    sudo apt-get update
    sudo apt-get install -y php7.0-cli php7.0-intl php7.0-curl php7.0-xml nodejs npm
    sudo npm install -g bower
    sudo ln -s /usr/local/bin/bower /usr/bin/bower
    sudo ln -s /usr/bin/nodejs /usr/bin/node
    cd /vagrant
    curl -sS https://getcomposer.org/installer | php
    ./composer.phar install
    php app/console server:start 0.0.0.0:8000
  SHELL
end
