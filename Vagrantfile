VAGRANTFILE_API_VERSION = '2'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'fnando/hellobits-trusty64'
  config.vm.network :forwarded_port, guest: 3000, host: 3000
  config.ssh.insert_key = false

  config.vm.provider :virtualbox do |vb|
    vb.memory = 1024
    vb.customize [
      'setextradata', :id,
      'VBoxInternal/Devices/ahci/0/LUN#[0]/Config/IgnoreFlush', '1'
    ]
  end
end