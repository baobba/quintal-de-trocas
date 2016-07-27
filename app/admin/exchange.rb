ActiveAdmin.register Exchange do

# See permitted parameters documentation:
# https://github.com/activeadmin/activeadmin/blob/master/docs/2-resource-customization.md#setting-up-strong-parameters
#
  # permit_params :user_id
#
# or
#
  # permit_params do
  #   permitted << :user_id
  #   permitted
  # end
  index do
    column :id
    column :toy_from
    column :toy_to
    column :user_id
    column :exchange_type
    column :exchange_deliver
    column :finalized
    column :finalized_at
    column :accepted
    column :created_at
    column :updated_at
    column :reason
  end

end
