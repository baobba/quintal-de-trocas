mysql_service node['quintal']['database_name'] do
  port '3306'
  bind_address '0.0.0.0'
  initial_root_password node['mysql']['server_root_password']
  action [:create, :start]
end

mysql_client 'default' do
    action :create
end

mysql2_chef_gem 'default' do
    action :install
end

mysql_connection_info = {
  :hostname => 'localhost',
  :username => 'root',
  :password => node['mysql']['server_root_password'],
  :socket => '/run/mysql-quintal/mysqld.sock'
}

database node['quintal']['database_name'] do
  connection mysql_connection_info
  provider   Chef::Provider::Database::Mysql
  action     :create
end

database node['quintal']['test_database_name'] do
  connection mysql_connection_info
  provider   Chef::Provider::Database::Mysql
  action     :create
end

database node['quintal']['database_name'] do
  connection mysql_connection_info
  provider   Chef::Provider::Database::Mysql
  sql { ::File.open('/var/www/quintal/dump/dump.sql').read }
  action :query
end
