class CreateOrders < ActiveRecord::Migration
  def change
    create_table :orders do |t|
      t.string :code
      t.string :title
      t.string :price
      t.string :status
      t.references :user
      t.references :toy

      t.timestamps
    end
    add_index :orders, :user_id
  end
end
