#!/usr/bin/env bash

cd /home/vagrant

# update APT repositories before installing anything else
sudo apt-get update

sudo /usr/sbin/update-locale LANG=en_US.UTF-8 LC_ALL=en_US.UTF-8

#install required packages
sudo apt-get install -y build-essential git curl libxslt1-dev libxml2-dev libssl-dev libpq-dev

# install node.js and npm the expected way
sudo apt-get install -y nodejs
sudo apt-get install -y npm

# RVM
gpg --keyserver hkp://keys.gnupg.net --recv-keys 409B6B1796C275462A1703113804BB82D39DC0E3
curl -sSL https://get.rvm.io | bash -s stable

# RUBY

source $HOME/.rvm/scripts/rvm

rvm use --default --install 2.2

shift

if (( $# ))
then gem install $@
fi

rvm cleanup all


gem source -r http://rubygems.org/
gem install bundler
gem install rails --no-rdoc --no-ri
bundle install
