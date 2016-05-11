include_recipe "php"
include_recipe "apt"


php_fpm_pool "default" do
  action :install
end

php_pear "mongo" do
  zend_extensions ['mongo.so']
  action :install
end

package "libmemcached-dev" do
    action :install
end

package "php5-memcached" do
    action :install
end

package "php5-memcache" do
    action :install
end

php_pear "memcached" do
  action :install
end

php_pear "xdebug" do
  zend_extensions ['xdebug.so']
  action :install
end

package "php5-gd" do
    action :install
end

package "php5-curl" do
    action :install
end
