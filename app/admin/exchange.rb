ActiveAdmin.register Exchange do

  menu parent: 'Trocas', label: 'Trocas'

  index :title => "Trocas" do
    id_column
    column :toy_from
    column :toy_to
    column :user_id
    column :exchange_type
    column :exchange_deliver
    column :finalized
    column :finalized_at
    column :accepted
    column :created_at
    column :reason
    
    actions
  end

end
