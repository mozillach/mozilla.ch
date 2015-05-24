# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 8000, host: 8000

  # provisioning with a shell script.
  config.vm.provision "shell", inline: <<-SHELL
    sudo apt-get update
    sudo apt-get install -y php5 php5-intl php5-curl
    cd /vagrant
    curl -sS https://getcomposer.org/installer | php
    ./composer.phar install
    php app/console server:start 0.0.0.0:8000
  SHELL
end
