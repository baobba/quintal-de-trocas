ActiveAdmin.register Exchange do

  config.sort_order = 'created_at_desc'
  menu parent: 'Trocas', label: 'Trocas'

  index :title => "Trocas" do
    id_column
    column "Solicitante", :user_id do |exc|
      link_to exc.user.name, edit_admin_user_path(exc.user.id)
    end
    column "Destinatário", :item_to do |exc|
      link_to exc.to_user.name, edit_admin_user_path(exc.to_user.id)
    end
    column "Brinquedo solicitado", :item_to do |exc|
      link_to exc.to_item.title, item_path(exc.to_item)
    end
    column "Tipo de troca", :exchange_type do |exc|
      if exc.exchange_type == "exchange"
        "Troca"
      elsif exc.exchange_type == "credit"
        "Crédito"
      else
        "--"
      end
    end
    column "Troca efetuada?", :finalized
    column "Troca aceita?", :accepted do |exc|
      if exc.accepted == false
        raw "<span class='status_tag no'>Não</span>"
      elsif exc.accepted == true
        raw "<span class='status_tag yes'>Sim</span>"
      else
        "--"
      end
    end
    column "Troca solicitada em", :created_at
    column "Troca recusada?", :reason do |exc|
      exc.reason.blank? ? "--" : raw("Sim<br>Motivo: #{exc.reason}")
    end
    
    actions
  end

end
