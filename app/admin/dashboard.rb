ActiveAdmin.register_page "Dashboard" do

  menu priority: 1, label: proc{ I18n.t("active_admin.dashboard") }

  content title: proc{ I18n.t("active_admin.dashboard") } do
    # div class: "blank_slate_container", id: "dashboard_default_message" do
    #   span class: "blank_slate" do
    #     span I18n.t("active_admin.dashboard_welcome.welcome")
    #     small I18n.t("active_admin.dashboard_welcome.call_to_action")
    #   end
    # end

    columns do
      column do
        panel "Usuários nos últimos 7 dias" do
          User.where('created_at > ?', Date.today-1.week).count
        end
      end
      column do
        panel "Brinquedos nos últimos 7 dias" do
          Item.where('created_at > ?', Date.today-1.week).count
        end
      end
      column do
        panel "Trocas iniciadas nos últimos 7 dias" do
          Exchange.where('created_at > ?', Date.today-1.week).count
        end
      end
      column do
        panel "Trocas aceitas nos últimos 7 dias" do
          Exchange.where(accepted: true).where('created_at > ?', Date.today-1.week).count
        end
      end
    end
    columns do
      column do
        panel "Qtdade usuários" do
          User.count
        end
      end
      column do
        panel "Qtdade brinquedos" do
          Item.count
        end
      end
      column do
        panel "Qtdade usuários com brinquedos cadastrados" do
          User.joins(:items).count
        end
      end
      column do
        panel "Qtdade Trocas" do
          Exchange.count
        end
      end
      column do
        panel "Qtdade Trocas finalizadas" do
          Exchange.where(finalized: true).count
        end
      end
      column do
        panel "Qtdade mensagens" do
          ExchangeMessage.count
        end
      end
      
    end
    columns do

      column do
        panel "Últimos briquedos adicionados" do
          table_for Item.order("created_at desc").limit(20) do
            column "Title",        :title
            column "Faixa etária", :item_age_id
            column "Categoria",    :item_category_id
            column "Data",         :created_at
          end
        end
      end
      column do
        panel "Últimos usuários adicionados" do
          table_for User.order("created_at desc").limit(20) do
            column "Nome",   :name
            column "Email",  :email
            column "Cidade", :city
            column "Estado", :state
            column "Data",   :created_at
          end
        end
      end
    end

  end
end
