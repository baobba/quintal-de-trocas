ActiveAdmin.register Article do

  menu label: 'Notícias'

  index title: 'Notícias' do
    column :title
    column :category
    column :active
    column :user
    column :published_at
    column :created_at

    actions
  end

end
