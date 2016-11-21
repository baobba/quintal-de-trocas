class AddPriceToItems < ActiveRecord::Migration
  def change
    add_column :items, :price, :decimal, :precision => 8, :scale => 2
    add_column :items, :weight, :integer
    add_column :items, :width, :integer
    add_column :items, :height, :integer
    add_column :items, :length, :integer
    add_column :items, :stock, :integer
  end
end
