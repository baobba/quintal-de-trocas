ActiveAdmin.register ToyAge do

  menu parent: "Brinquedos", label: 'Faixa etária'

  index :title => "Faixa etária" do
    id_column
    column "title"
    column "created_at"
    column "updated_at"

    actions
  end

end
