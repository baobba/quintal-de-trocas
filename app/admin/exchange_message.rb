ActiveAdmin.register ExchangeMessage do

  config.sort_order = 'created_at_desc'
  menu parent: "Trocas", label: 'Mensagens'

  index :title => "Mensagens de trocas" do

    id_column
    column :user_id
    column :exchange_id
    column :user_from
    column :user_to
    column :message
    column :read_at
    column :created_at

    actions
  end

end
