ActiveAdmin.register_page "Dashboard" do

  menu priority: 1, label: proc{ I18n.t("active_admin.dashboard") }

  content title: proc{ I18n.t("active_admin.dashboard") } do
    # div class: "blank_slate_container", id: "dashboard_default_message" do
    #   span class: "blank_slate" do
    #     span I18n.t("active_admin.dashboard_welcome.welcome")
    #     small I18n.t("active_admin.dashboard_welcome.call_to_action")
    #   end
    # end

    "Hey"

    columns do
      column do
        panel "Últimos briquedos adicionados" do
          Toy.all.map do |toy|
            li link_to(toy.title, toy_path(toy))
            li "<strong>Faixa etária:</strong> #{toy.toy_age.title}&nbsp;&nbsp;".html_safe
            li "<strong>Categoria:</strong> #{toy.toy_category.title}&nbsp;&nbsp;".html_safe
          end
        end
      end
      column do
        panel "Últimos usuários adicionados" do
          User.all.map do |user|
            li user.email
          end
        end
      end
    end

    # Here is an example of a simple dashboard with columns and panels.
    #
    # columns do
    #   column do
    #     panel "Recent Posts" do
    #       ul do
    #         Post.recent(5).map do |post|
    #           li link_to(post.title, admin_post_path(post))
    #         end
    #       end
    #     end
    #   end

    #   column do
    #     panel "Info" do
    #       para "Welcome to ActiveAdmin."
    #     end
    #   end
    # end
  end # content
end
