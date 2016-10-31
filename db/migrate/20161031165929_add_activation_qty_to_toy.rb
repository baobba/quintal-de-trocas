class AddActivationQtyToToy < ActiveRecord::Migration
  def change
    add_column :toys, :activate_qty, :integer, default: 0
  end
end
