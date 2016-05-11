include_recipe 'nginx'

node.override['nginx']['gzip'] = "off"

begin
  t = resources("template[#{node['nginx']['dir']}/sites-available/default]")
  t.source "default-site.erb"
  t.cookbook "quintal"
rescue Chef::Exceptions::ResourceNotFound
  Chef::Log.warn "could not find template your template override!"
end
