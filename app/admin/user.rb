ActiveAdmin.register User do

  config.sort_order = 'created_at_desc'
  menu label: 'Usuários'

  index :title => "Usuários" do
    id_column
    column :name
    column :email
    # column :encrypted_password
    # column :reset_password_token
    # column :reset_password_sent_at
    # column :remember_created_at
    column :sign_in_count
    # column :current_sign_in_at
    # column :last_sign_in_at
    # column :current_sign_in_ip
    # column :last_sign_in_ip
    column :created_at
    # column :updated_at
    # column :username
    column :birthday
    # column :phone
    # column :gender
    column :city
    column :state
    column :zipcode
    # column :latitude
    # column :longitude
    # column :avatar
    # column :deleted_at
    column :street
    column :complement
    column :neighborhood
    column :newsletter
    column :confirmed_at

    actions
  end

  filter :name
  filter :email
  filter :city
  filter :state
  filter :zipcode
  filter :street
  filter :neighborhood
  filter :newsletter
  filter :admin

end
