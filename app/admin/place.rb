ActiveAdmin.register Place do

  menu label: 'Pontos de troca'

  index :title => "Pontos de troca" do
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
