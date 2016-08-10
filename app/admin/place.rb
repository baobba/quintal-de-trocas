ActiveAdmin.register Place do

  config.sort_order = 'created_at_desc'
  menu label: 'Pontos de troca'

  index :title => "Pontos de troca" do
    id_column
    column :title
    column :office_hours
    column :phone
    column :user_id
    column :city
    column :state
    column :created_at
    column :is_active

    actions
  end
    

end
