class AddCreditFromToExchange < ActiveRecord::Migration

  def up
    add_column :exchanges, :credit_offer, :boolean
    rename_column :credits, :is_available, :expired_at
    change_column :credits, :expired_at, :datetime, :default => nil
  end

  def down
    remove_column :exchanges, :credit_offer
    rename_column :credits, :expired_at, :is_available
    change_column :credits, :is_available, :boolean
  end
end
