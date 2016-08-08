ActiveAdmin.register Toy do

# See permitted parameters documentation:
# https://github.com/activeadmin/activeadmin/blob/master/docs/2-resource-customization.md#setting-up-strong-parameters
#
permit_params :title, :description, :toy_category_id, :toy_category_id, :user_id, :image
#
# or
#
# permit_params do
#   permitted = [:permitted, :attributes]
#   permitted << :other if params[:action] == 'create' && current_user.admin?
#   permitted
# end
  menu parent: 'Brinquedos', label: 'Lista de brinquedos'

  index do
    id_column
    column "Título", :title
    column "Categoria", :toy_category_id
    column "Faixa etária", :toy_age_id
    column "Usuário", :user_id
    column "CEP", :zipcode
    column "Data cadastro", :created_at
    # column "xx", :image
    column "Ativo?", :is_active

    actions
  end


end
