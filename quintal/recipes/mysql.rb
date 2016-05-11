include_recipe "apt"
include_recipe "dotdeb"
include_recipe "php::default"
include_recipe "php::module_mysql"
include_recipe "composer"
include_recipe "nodejs"

mysql_chef_gem 'default' do
    action :install
end

mysql_service 'default' do
    port '3306'
    version '5.6'
    initial_root_password node['mysql']['server_root_password']
    provider Chef::Provider::MysqlServiceSysvinit
    action [:create, :start]
end

mysql_client 'default' do
    action :create
end

link "/etc/my.cnf" do
    to "/etc/mysql-default/my.cnf"
end

mysql_database node['quintal']['database_name'] do
	connection(
		:host => 'localhost',
		:username => 'root',
		:password => node['mysql']['server_root_password'],
        :socket => '/var/run/mysql-default/mysqld.sock'
	)
	action :create
end

mysql_database node['quintal']['test_database_name'] do
	connection(
		:host => 'localhost',
		:username => 'root',
		:password => node['mysql']['server_root_password'],
        :socket => '/var/run/mysql-default/mysqld.sock'
	)
	action :create
end

execute "mysql-start-dump" do
  command "mysql -S /var/run/mysql-default/mysqld.sock -u root -p#{node['mysql']['server_root_password']} < /var/www/quintal/dump.sql"      
end