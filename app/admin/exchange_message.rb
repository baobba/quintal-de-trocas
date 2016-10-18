ActiveAdmin.register ExchangeMessage do

  config.sort_order = 'created_at_desc'
  menu parent: "Trocas", label: 'Mensagens'

  index :title => "Mensagens de trocas" do

    id_column
    column "Remetente", :user_from do |msg|
      link_to msg.from_user.name, edit_admin_user_path(msg.from_user.id)
    end
    column "Destinatário", :user_to do |msg|
      link_to msg.to_user.name, edit_admin_user_path(msg.to_user.id)
    end
    column "Troca", :exchange_id
    column "Mensagem", :message
    column "Lido em", :read_at
    column "Enviado em", :created_at

    actions
  end

end
