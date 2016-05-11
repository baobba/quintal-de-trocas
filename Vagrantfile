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
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder "./", "/var/www"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  # config.vm.provider :virtualbox do |vb|
  #   # Don't boot with headless mode
  #   vb.gui = true
  #
  #   # Use VBoxManage to customize the VM. For example to change memory:
  #   vb.customize ["modifyvm", :id, "--memory", "1024"]
  # end
  #
  # View the documentation for the provider you're using for more
  # information on available options.

  # The path to the Berksfile to use with Vagrant Berkshelf
  config.berkshelf.berksfile_path = "./quintal/Berksfile"

  # Enabling the Berkshelf plugin. To enable this globally, add this configuration
  # option to your ~/.vagrant.d/Vagrantfile file
  config.berkshelf.enabled = true

  # An array of symbols representing groups of cookbook described in the Vagrantfile
  # to exclusively install and copy to Vagrant's shelf.
  # config.berkshelf.only = []

  # An array of symbols representing groups of cookbook described in the Vagrantfile
  # to skip installing and copying to Vagrant's shelf.
  # config.berkshelf.except = []

  config.vm.provision :chef_solo do |chef|

    chef.json = {
      memcached: {
        memory: "128",
        enabled: "yes",
        listen_ip: "0.0.0.0"
      },
      mysql: {
        server_root_password: "root",
        server_debian_password: "root",
        server_repl_password: "root"
      },
      quintal: {
          hostname: "quintal.dev",
          database_name: "quintal",
          test_database_name: "quintal_test"
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
      'recipe[quintal::mysql]',
      'recipe[php]',
      'recipe[quintal::php]',
      'recipe[composer]'
    ]
  end
end
