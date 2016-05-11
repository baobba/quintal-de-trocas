include_recipe "nodejs"

execute "npm::install" do
    command "npm i -g npm ; "
end

execute "npm::install" do
    command "npm install -g bower;"
end

execute "npm::install" do
    command "npm install -g gulp;"
end
