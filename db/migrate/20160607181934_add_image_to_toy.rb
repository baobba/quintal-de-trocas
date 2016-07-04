class AddImageToToy < ActiveRecord::Migration
  def change
    add_column :toys, :image, :string
  end
end
