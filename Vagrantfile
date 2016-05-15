# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

Vagrant.require_version '>= 1.5.0'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.hostname = 'quintal.dev'

  #   $ vagrant plugin install vagrant-omnibus
  if Vagrant.has_plugin?("vagrant-omnibus")
    config.omnibus.chef_version = 'latest'
  end

  config.vm.box = 'bento/ubuntu-14.04'
  config.vm.network :private_network, ip: "192.168.20.13"
  #config.vm.network "forwarded_port", guest: 80, host: 80
  #config.vm.network "forwarded_port", guest: 3306, host: 3306

  config.vm.synced_folder "./", "/var/www"

  
  config.berkshelf.berksfile_path = "./quintal/Berksfile"


  config.berkshelf.enabled = true

  # config.berkshelf.only = []

  # config.berkshelf.except = []

  config.vm.provision :chef_solo do |chef|

    chef.json = {
      memcached: {
        memory: "128",
        enabled: "yes",
        listen_ip: "0.0.0.0"
      },
      mysql: {
        server_root_password: "quintal",
        server_debian_password: "",
        server_repl_password: ""
      },
      quintal: {
          hostname: "quintal.dev",
          database_name: "quintal",
          test_database_name: "quintal-test"
      }
    }

    chef.run_list = [
      'recipe[quintal::default]',
      'recipe[quintal::nano]',
      'recipe[quintal::htop]',
      'recipe[build-essential::default]',
      'recipe[zsh]',
      'recipe[git::default]',
      'recipe[memcached-cookbook]',
      'recipe[nginx::default]',
      'recipe[quintal::nginx]',
      'recipe[nodejs::nodejs]',
      'recipe[quintal::nodejs]',
      'recipe[php]',
      'recipe[quintal::php]',
      'recipe[composer]',
      'recipe[quintal::mysql]'
    ]
  end
end
