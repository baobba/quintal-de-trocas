ActiveAdmin.register User do

  menu label: 'Usuários'

  index :title => "Usuários" do
    column :id
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

    actions
  end

  filter :encrypted_password
  # filter :current_sign_in_at
  # filter :sign_in_count
  # filter :created_at

end
