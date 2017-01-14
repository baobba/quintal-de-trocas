ActiveAdmin.register ItemCategory do

  menu parent: "Brinquedos", label: 'Categorias'

  index :title => "Categorias" do
    id_column
    column "title"
    column "created_at"
    column "updated_at"

    actions
  end

end
