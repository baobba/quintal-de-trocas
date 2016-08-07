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
    column :title
    column :toy_category_id
    column :toy_age_id
    column :user_id
    column :zipcode
    column :created_at
    # column :image
    column :is_active

    actions
  end


end
