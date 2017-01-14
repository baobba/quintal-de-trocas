class AddPriceToItems < ActiveRecord::Migration
  def change
<<<<<<< HEAD
    add_column :items, :price, :decimal, :precision => 14, :scale => 2
=======
    add_column :items, :price, :decimal, :precision => 8, :scale => 2
>>>>>>> cf00e2e780b10aa9b0512eb642e00095b51aac69
    add_column :items, :weight, :integer
    add_column :items, :width, :integer
    add_column :items, :height, :integer
    add_column :items, :length, :integer
    add_column :items, :stock, :integer
  end
end
