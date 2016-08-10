ActiveAdmin.register Article do

  config.sort_order = 'created_at_desc'
  menu label: 'Notícias'

  index title: 'Notícias' do
    id_column
    column :title
    column :category
    column :active
    column :user
    column :published_at
    column :created_at

    actions
  end

end
