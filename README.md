# Quintal de trocas

Repositório do projeto Quintal de Trocas

## Requisitos Básicos

* Virtualbox
* Vagrant (vagrantup.com)
* Chef SDK (https://downloads.chef.io/chef-dk/)

### Instruções

Abra seu terminal após o clone do projeto e digite:

`vagrant up`

Edite o seu arquivo de hosts local e crie uma nova entrada indicando o endereço do seu ambiente local definido no Vagrantfile.

`192.168.20.13  quintal.dev` 

Aguarde a criação do ambiente + instalação das dependencias que são:

* nano
* htop
* pacote build-essentials do linux com compiladores como o GCC e outros
* zsh
* git
* memcached
* nginx (instalação + virtualhost)
* nodeJS
* PHP 5 + Composer
* MySQL 

Infelizmente por algum motivo o dump ainda não roda automaticamente, é necessário a maquina virtual e se conectar ao mysql e executar a execução do dump manualmente:

`mysql -S /run/mysql-quintal/mysqld.sock -uroot -pquintal < /var/www/quintal/dump/dump.sql`