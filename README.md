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

`192.168.33.10  quintal.dev` 

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


** Itens pendentes: Mysql + Dump